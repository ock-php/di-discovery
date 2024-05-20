<?php

declare(strict_types = 1);

namespace Ock\DID\EggList;

use Ock\ClassDiscovery\Exception\DiscoveryException;
use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\ClassDiscovery\Shared\ReflectionClassesIAHavingBase;
use Ock\ClassDiscovery\Util\AttributesUtil;
use Ock\DID\Attribute\Parameter\GetArgument;
use Ock\DID\Attribute\ParametricService;
use Ock\DID\Callback\CurryConstruct;
use Ock\Helpers\Util\MessageUtil;
use Ock\Egg\Egg\Egg_CallableCall;
use Ock\Egg\Egg\EggInterface;
use Ock\Egg\ParamToEgg\ParamToEggInterface;

class EggList_Discovery_ParameterizedServiceAttribute extends ReflectionClassesIAHavingBase implements EggListInterface {

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
      /** @var \Ock\DID\Attribute\ParametricService[] $serviceAttributes */
      $serviceAttributes = AttributesUtil::getAll($reflectionClass, ParametricService::class);
      if ($serviceAttributes) {
        $egg = $this->onClass($reflectionClass);
        foreach ($serviceAttributes as $serviceAttribute) {
          $serviceId = $serviceAttribute->reflectorGetServiceId($reflectionClass);
          $eggs[$serviceId] = $egg;
        }
      }
      foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        /** @var \Ock\DID\Attribute\ParametricService[] $serviceAttributes */
        $serviceAttributes = AttributesUtil::getAll($reflectionMethod, ParametricService::class);
        if ($serviceAttributes) {
          $egg = $this->onMethod($reflectionClass, $reflectionMethod);
          foreach ($serviceAttributes as $serviceAttribute) {
            $serviceId = $serviceAttribute->reflectorGetServiceId($reflectionMethod);
            $eggs[$serviceId] = $egg;
          }
        }
      }
    }
    return $eggs;
  }

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Ock\Egg\Egg\EggInterface
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  private function onClass(\ReflectionClass $reflectionClass): EggInterface {
    if (!$reflectionClass->isInstantiable()) {
      throw new MalformedDeclarationException(sprintf(
        'Class %s is not instantiable. Attribute %s is not allowed.',
        $reflectionClass->getName(),
        ParametricService::class,
      ));
    }
    $argEggs = [];
    $curryArgNames = [];
    foreach ($reflectionClass->getConstructor()?->getParameters() ?? [] as $parameter) {
      $attributes = AttributesUtil::getAll($parameter, GetArgument::class);
      if ($attributes !== []) {

      }
      $argEggs[] = $this->paramToEgg->paramGetEgg($parameter);
    }
    return CurryConstruct::egg(
      $reflectionClass->getName(),
      $argEggs,
      $curryArgNames,
    );
  }

  /**
   * @param \ReflectionClass $reflectionClass
   * @param \ReflectionMethod $reflectionMethod
   *
   * @return \Ock\Egg\Egg\EggInterface
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  private function onMethod(\ReflectionClass $reflectionClass, \ReflectionMethod $reflectionMethod): EggInterface {
    if (!$reflectionMethod->isStatic()) {
      throw new MalformedDeclarationException(sprintf(
        'Method %s is not static. Attribute %s is not allowed.',
        MessageUtil::formatReflector($reflectionMethod),
        ParametricService::class,
      ));
    }
    $argEggs = $this->buildArgEggs($reflectionMethod->getParameters());
    return Egg_CallableCall::createFixed(
      [$reflectionClass->getName(), $reflectionMethod->getName()],
      $argEggs,
    );
  }

  /**
   * @param \ReflectionParameter[] $parameters
   *
   * @return \Ock\Egg\Egg\EggInterface[]
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
      $argEggs[] = $egg;
    }
    return $argEggs;
  }

}
