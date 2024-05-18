<?php

declare(strict_types = 1);

namespace Donquixote\DID\CTVList;

use Donquixote\ClassDiscovery\Exception\DiscoveryException;
use Donquixote\ClassDiscovery\Shared\ReflectionClassesIAHavingBase;
use Donquixote\ClassDiscovery\Util\AttributesUtil;
use Donquixote\DID\Attribute\Parameter\CallServiceMethodWithArguments;
use Donquixote\DID\Attribute\Parameter\CallServiceWithArguments;
use Donquixote\DID\Attribute\Parameter\GetArgument;
use Donquixote\DID\Attribute\ServiceDefinitionAttributeInterface;
use Donquixote\DID\Callback\CurryCall;
use Donquixote\DID\Callback\CurryConstruct;
use Donquixote\Helpers\Util\MessageUtil;
use Ock\Egg\Egg\Egg_CallableCall;
use Ock\Egg\Egg\Egg_Construct;
use Ock\Egg\Egg\Egg_ServiceId;
use Ock\Egg\Egg\EggInterface;
use Ock\Egg\ParamToEgg\ParamToEggInterface;

class CTVList_Discovery_ServiceAttribute extends ReflectionClassesIAHavingBase implements CTVListInterface {

  /**
   * Constructor.
   *
   * @param \Ock\Egg\ParamToEgg\ParamToEggInterface $paramToEgg
   */
  public function __construct(
    private readonly ParamToEggInterface $paramToEgg,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getCTVs(): array {
    $eggs = [];
    /** @var \ReflectionClass $reflectionClass */
    foreach ($this->itReflectionClasses() as $reflectionClass) {
      /** @var \Donquixote\DID\Attribute\ServiceDefinitionAttributeInterface[] $serviceAttributes */
      $serviceAttributes = AttributesUtil::getAll($reflectionClass, ServiceDefinitionAttributeInterface::class);
      if ($serviceAttributes) {
        foreach ($serviceAttributes as $serviceAttribute) {
          $serviceId = $serviceAttribute->reflectorGetServiceId($reflectionClass);
          $egg = $this->onClass(
            $reflectionClass,
            $serviceAttribute->isParametricService(),
          );
          $eggs[$serviceId] = $egg;
        }
      }
      foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        /** @var \Donquixote\DID\Attribute\ServiceDefinitionAttributeInterface[] $serviceAttributes */
        $serviceAttributes = AttributesUtil::getAll($reflectionMethod, ServiceDefinitionAttributeInterface::class);
        if ($serviceAttributes) {
          foreach ($serviceAttributes as $serviceAttribute) {
            $serviceId = $serviceAttribute->reflectorGetServiceId($reflectionMethod);
            $egg = $this->onMethod(
              $reflectionClass,
              $reflectionMethod,
              $serviceAttribute->isParametricService(),
            );
            $eggs[$serviceId] = $egg;
          }
        }
      }
    }
    return $eggs;
  }

  /**
   * @param \ReflectionClass $reflectionClass
   * @param bool $isCallableService
   *
   * @return \Ock\Egg\Egg\EggInterface
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  private function onClass(\ReflectionClass $reflectionClass, bool $isCallableService): EggInterface {
    if (!$reflectionClass->isInstantiable()) {
      throw new DiscoveryException(sprintf(
        'Class %s is not instantiable.',
        $reflectionClass->getName(),
      ));
    }
    $parameters = $reflectionClass->getConstructor()?->getParameters() ?? [];
    if (!$isCallableService) {
      $argEggs = $this->buildArgCTVs($parameters);
      return new Egg_Construct(
        $reflectionClass->getName(),
        $argEggs,
      );
    }
    $argEggs = $this->buildCallableArgCTVs($parameters, $curryArgNames, $callableArgEggs);
    return CurryConstruct::ctv(
      $reflectionClass->getName(),
      $argEggs,
      $curryArgNames,
      $callableArgEggs,
    );
  }

  /**
   * @param \ReflectionClass $reflectionClass
   * @param \ReflectionMethod $reflectionMethod
   * @param bool $isCallableService
   *
   * @return \Ock\Egg\Egg\EggInterface
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  private function onMethod(\ReflectionClass $reflectionClass, \ReflectionMethod $reflectionMethod, bool $isCallableService): EggInterface {
    if (!$reflectionMethod->isStatic()) {
      throw new DiscoveryException(sprintf(
        'Method %s is not static.',
        MessageUtil::formatReflector($reflectionMethod),
      ));
    }
    $parameters = $reflectionClass->getConstructor()?->getParameters();
    if (!$isCallableService) {
      $argEggs = $this->buildArgCTVs($reflectionMethod->getParameters());
      return Egg_CallableCall::createFixed(
        [$reflectionClass->getName(), $reflectionMethod->getName()],
        $argEggs,
      );
    }
    $argEggs = $this->buildCallableArgCTVs($parameters, $curryArgNames, $callableArgEggs);
    return CurryCall::ctv(
      [$reflectionClass->getName(), $reflectionMethod->getName()],
      $argEggs,
      $curryArgNames,
      $callableArgEggs,
    );
  }

  /**
   * @param \ReflectionParameter[] $parameters
   *
   * @return list<\Ock\Egg\Egg\EggInterface>
   *
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  private function buildArgCTVs(array $parameters): array {
    $argEggs = [];
    foreach ($parameters as $parameter) {
      $egg = $this->paramToEgg->paramGetCTV($parameter);
      if ($egg === NULL) {
        throw new DiscoveryException(sprintf(
          'Cannot resolve parameter %s for container-to-value.',
          MessageUtil::formatReflector($parameter),
        ));
      }
      $argEggs[$parameter->getName()] = $egg;
    }
    returN $argEggs;
  }

  /**
   * @param \ReflectionParameter[] $parameters
   * @param array|null $curryArgsMap
   * @param array|null $callableArgEggs
   *
   * @return list<\Ock\Egg\Egg\EggInterface|null>
   *
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  private function buildCallableArgCTVs(array $parameters, ?array &$curryArgsMap, ?array &$callableArgEggs): array {
    $argEggs = [];
    foreach ($parameters as $parameter) {
      $egg = $this->paramToEgg->paramGetCTV($parameter);
      $argEggs[$parameter->getName()] = $egg;
      if ($egg !== NULL) {
        continue;
      }
      if ($attribute = AttributesUtil::getSingle($parameter, GetArgument::class)) {
        $curryArgsMap[$parameter->getName()] = $attribute->position;
      }
      elseif ($attribute = AttributesUtil::getSingle($parameter, CallServiceMethodWithArguments::class)) {
        $callableArgEggs[$attribute->getName()] = CurryCall::ctvMethodCall(
          new Egg_ServiceId($attribute->serviceId),
          $attribute->method,
          [],
          $attribute->forwardArgsMap,
        );
      }
      elseif ($attribute = AttributesUtil::getSingle($parameter, CallServiceWithArguments::class)) {
        $callableArgEggs[$attribute->getName()] = CurryCall::ctv(
          new Egg_ServiceId($attribute->paramGetServiceId($parameter)),
          $attribute->method,
          [],
          $attribute->forwardArgsMap,
        );
      }
      else {
        throw new DiscoveryException(sprintf(
          'Cannot resolve parameter %s for container-to-value, not even with arguments.',
          MessageUtil::formatReflector($parameter),
        ));
      }
    }
    return $argEggs;
  }

}
