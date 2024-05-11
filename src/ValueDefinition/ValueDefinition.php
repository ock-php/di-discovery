<?php

declare(strict_types=1);

namespace Donquixote\DID\ValueDefinition;

use Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Donquixote\ClassDiscovery\Reflection\MethodReflection;

/**
 * Static factories for ValueDefinitionInterface objects.
 */
class ValueDefinition {

  /**
   * @param \Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface $reflector
   * @param array $args
   *
   * @return \Donquixote\DID\ValueDefinition\ValueDefinitionInterface
   */
  public static function fromReflector(FactoryReflectionInterface $reflector, array $args): ValueDefinitionInterface {
    if ($reflector instanceof MethodReflection) {
      return new ValueDefinition_Call(
        $reflector->getStaticCallableArray(),
        $args,
      );
    }
    return new ValueDefinition_Construct(
      $reflector->getClassName(),
      $args,
    );
  }

}
