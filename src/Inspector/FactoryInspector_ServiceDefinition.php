<?php

declare(strict_types=1);

namespace Ock\DID\Inspector;

use Ock\ClassDiscovery\Exception\DiscoveryException;
use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\ClassDiscovery\Inspector\FactoryInspectorInterface;
use Ock\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Ock\ClassDiscovery\Reflection\MethodReflection;
use Ock\ClassDiscovery\Util\AttributesUtil;
use Ock\DID\Attribute\Parameter\ServiceArgumentAttributeInterface;
use Ock\DID\Attribute\ServiceDefinitionAttributeBase;
use Ock\DID\Attribute\ServiceDiscoveryUtil;
use Ock\DID\ServiceDefinition\ServiceDefinition;
use Ock\DID\ValueDefinition\ValueDefinition;
use Ock\DID\ValueDefinition\ValueDefinition_Parametric;
use Ock\Helpers\Util\MessageUtil;

/**
 * @template-implements FactoryInspectorInterface<\Ock\DID\ServiceDefinition\ServiceDefinition>
 */
class FactoryInspector_ServiceDefinition implements FactoryInspectorInterface {

  /**
   * {@inheritdoc}
   */
  public function findInFactory(FactoryReflectionInterface $reflector): \Iterator {
    $attributes = AttributesUtil::getAll($reflector, ServiceDefinitionAttributeBase::class);
    foreach ($attributes as $attribute) {
      if (!$reflector->isCallable()) {
        throw new DiscoveryException(sprintf(
          'Attribute %s is not allowed on %s.',
          \get_class($attribute),
          $reflector->getDebugName(),
        ));
      }
      $parameters = $reflector->getParameters();
      $args = $this->buildArgumentDefinitions($parameters);
      try {
        $returnClass = $reflector->getReturnClass();
      }
      catch (\ReflectionException) {
        continue;
      }
      if ($returnClass === null) {
        throw new MalformedDeclarationException(\sprintf(
          'Expected a class-like %s declaration on %s.',
          $reflector instanceof \ReflectionParameter ? 'type' : 'return type',
          MessageUtil::formatReflector($reflector),
        ));
      }
      $type = ServiceDiscoveryUtil::classGetTypeName($returnClass);
      $serviceId = $attribute->serviceId ?? $type;
      if ($attribute->serviceIdSuffix !== NULL) {
        $serviceId .= '.' . $attribute->serviceIdSuffix;
      }
      if ($attribute->isParametric()) {
        $serviceId = 'get.' . $serviceId;
        // @todo Verify if this actually works.
        $value = $reflector instanceof MethodReflection
          ? $reflector->getStaticCallableArray()
          : $reflector->getClassName();
        $valueDefinition = new ValueDefinition_Parametric($value);
      }
      else {
        $valueDefinition = ValueDefinition::fromReflector($reflector, $args);
      }
      yield new ServiceDefinition(
        $serviceId,
        $reflector->getClassName(),
        $valueDefinition,
      );
    }
  }

  /**
   * @param array $parameters
   *
   * @return array
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  protected function buildArgumentDefinitions(array $parameters): array {
    $args = [];
    foreach ($parameters as $parameter) {
      $args[] = AttributesUtil::requireSingle($parameter, ServiceArgumentAttributeInterface::class)
        ->getArgumentDefinition($parameter);
    }
    return $args;
  }

}
