<?php

declare(strict_types=1);

namespace Ock\DID\ValueDefinition;

use Ock\Reflection\FactoryReflectionInterface;
use Ock\Reflection\MethodReflection;

/**
 * Static factories for ValueDefinitionInterface objects.
 */
class ValueDefinition {

  /**
   * @param \Ock\Reflection\FactoryReflectionInterface $reflector
   * @param array $args
   *
   * @return \Ock\DID\ValueDefinition\ValueDefinitionInterface
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
