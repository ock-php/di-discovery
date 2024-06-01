<?php

declare(strict_types=1);

namespace Ock\DID\Inspector;

use Ock\ClassDiscovery\Exception\DiscoveryException;
use Ock\ClassDiscovery\Inspector\FactoryInspectorInterface;
use Ock\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Ock\DID\Attribute\Service;
use Ock\DID\Attribute\ServicesDiscovery;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @template-implements FactoryInspectorInterface<\Symfony\Component\DependencyInjection\Definition>
 */
#[ServicesDiscovery]
class FactoryInspector_SymfonyServices implements FactoryInspectorInterface {

  /**
   * {@inheritdoc}
   */
  public function findInFactory(FactoryReflectionInterface $reflector): \Iterator {
    $attributes = $reflector->getAttributes(Service::class);
    if (!$attributes) {
      return;
    }
    if (!$reflector->isCallable()) {
      throw new DiscoveryException(sprintf(
        'Attribute #[%s] is not allowed on %s.',
        \get_class($attributes[0]),
        $reflector->getDebugName(),
      ));
    }
    foreach ($attributes as $reflectionAttribute) {
      $attribute = $reflectionAttribute->newInstance();
      $class = $reflector->getReturnClassName();
      $id = $attribute->serviceId ?? $reflector->getClassName();
      if ($attribute->serviceIdSuffix !== NULL) {
        $id .= $attribute->serviceIdSuffix;
      }
      $definition = new Definition($class);
      $definition->setPublic(true);
      if (null !== $method = $reflector->getMethodName()) {
        if ($reflector->isStatic()) {
          $definition->setFactory([$reflector->getClassName(), $method]);
        }
        else {
          $definition->setFactory([new Reference($reflector->getClassName()), $method]);
        }
      }
      yield $id => $definition;
    }
  }

}
