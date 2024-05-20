<?php

declare(strict_types = 1);

namespace Ock\DID\EggList;

use Ock\ClassDiscovery\Exception\DiscoveryException;
use Ock\ClassDiscovery\Shared\ReflectionClassesIAHavingBase;
use Ock\ClassDiscovery\Util\AttributesUtil;
use Ock\DID\Attribute\Parameter\CallServiceMethodWithArguments;
use Ock\DID\Attribute\Parameter\CallServiceWithArguments;
use Ock\DID\Attribute\Parameter\GetArgument;
use Ock\DID\Attribute\ServiceDefinitionAttributeInterface;
use Ock\DID\Callback\CurryCall;
use Ock\DID\Callback\CurryConstruct;
use Ock\Egg\Egg\Egg_CallableCall;
use Ock\Egg\Egg\Egg_Construct;
use Ock\Egg\Egg\Egg_ServiceId;
use Ock\Egg\Egg\EggInterface;
use Ock\Egg\ParamToEgg\ParamToEggInterface;
use Ock\Helpers\Util\MessageUtil;

class EggList_Discovery_ServiceAttribute extends ReflectionClassesIAHavingBase implements EggListInterface {

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
  public function getEggs(): array {
    $eggs = [];
    /** @var \ReflectionClass $reflectionClass */
    foreach ($this->itReflectionClasses() as $reflectionClass) {
      /** @var \Ock\DID\Attribute\ServiceDefinitionAttributeInterface[] $serviceAttributes */
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
        /** @var \Ock\DID\Attribute\ServiceDefinitionAttributeInterface[] $serviceAttributes */
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
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
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
      $argEggs = $this->buildArgEggs($parameters);
      return new Egg_Construct(
        $reflectionClass->getName(),
        $argEggs,
      );
    }
    $argEggs = $this->buildCallableArgEggs($parameters, $curryArgNames, $callableArgEggs);
    return CurryConstruct::egg(
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
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
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
      $argEggs = $this->buildArgEggs($reflectionMethod->getParameters());
      return Egg_CallableCall::createFixed(
        [$reflectionClass->getName(), $reflectionMethod->getName()],
        $argEggs,
      );
    }
    $argEggs = $this->buildCallableArgEggs($parameters, $curryArgNames, $callableArgEggs);
    return CurryCall::egg(
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
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  private function buildArgEggs(array $parameters): array {
    $argEggs = [];
    foreach ($parameters as $parameter) {
      $egg = $this->paramToEgg->paramGetEgg($parameter);
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
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  private function buildCallableArgEggs(array $parameters, ?array &$curryArgsMap, ?array &$callableArgEggs): array {
    $argEggs = [];
    foreach ($parameters as $parameter) {
      $egg = $this->paramToEgg->paramGetEgg($parameter);
      $argEggs[$parameter->getName()] = $egg;
      if ($egg !== NULL) {
        continue;
      }
      if ($attribute = AttributesUtil::getSingle($parameter, GetArgument::class)) {
        $curryArgsMap[$parameter->getName()] = $attribute->position;
      }
      elseif ($attribute = AttributesUtil::getSingle($parameter, CallServiceMethodWithArguments::class)) {
        $callableArgEggs[$attribute->getName()] = CurryCall::eggMethodCall(
          new Egg_ServiceId($attribute->serviceId),
          $attribute->method,
          [],
          $attribute->forwardArgsMap,
        );
      }
      elseif ($attribute = AttributesUtil::getSingle($parameter, CallServiceWithArguments::class)) {
        $callableArgEggs[$attribute->getName()] = CurryCall::egg(
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
