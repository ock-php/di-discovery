<?php

declare(strict_types=1);

namespace Donquixote\DID\Util;

use Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Donquixote\Helpers\Util\MessageUtil;
use Donquixote\DID\Attribute\ReflectorAwareAttributeInterface;
use Donquixote\DID\Exception\MalformedDeclarationException;

/**
 * Helper methods to read attributes from reflectors.
 */
class AttributesUtil {

  /**
   * Finds and instantiates all attributes of a given type.
   *
   * @template T as object
   *
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty|\Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface $reflector
   * @param class-string<T> $name
   *
   * @return list<T>
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   */
  public static function getAll(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty|FactoryReflectionInterface $reflector,
    string $name,
  ): array {
    $instances = [];
    /** @var \ReflectionAttribute<T> $attribute */
    foreach ($reflector->getAttributes($name, \ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
      $instances[] = $instance = $attribute->newInstance();
      if ($instance instanceof ReflectorAwareAttributeInterface) {
        $instance->setReflector($reflector);
      }
    }
    return $instances;
  }

  /**
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty|\Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface $reflector
   * @param class-string $name
   *
   * @return bool
   *   TRUE, if exactly one attribute of the given type exists.
   *   FALSE, if no attribute of the given type exists.
   *   Exception otherwise.
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   *   More than one attribute of the given type.
   */
  public static function hasSingle(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty|FactoryReflectionInterface $reflector,
    string $name,
  ): bool {
    return self::getSingleAttribute($reflector, $name) !== null;
  }

  /**
   * Gets and instantiates the only attribute of a given type.
   *
   * @template T of object
   *
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty|\Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface $reflector
   * @param class-string<T> $name
   *
   * @return object
   * @phpstan-return T
   * @psalm-return T
   *   Instance from the single attribute.
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   *   None or more than one attribute of the given type.
   */
  public static function requireSingle(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty|FactoryReflectionInterface $reflector,
    string $name,
  ): object {
    $instance = self::getSingle($reflector, $name);
    if ($instance === NULL) {
      throw new MalformedDeclarationException(sprintf(
        'Required attribute %s missing on %s.',
        $name,
        MessageUtil::formatReflector($reflector),
      ));
    }
    return $instance;
  }

  /**
   * Gets and instantiates the first attribute of a given type, if exists.
   *
   * @template T of object
   *
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty|\Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface $reflector
   *   Element that has the attribute.
   * @param class-string<T> $name
   *   Expected attribute type/name.
   *
   * @return object|null
   *   Instance from the attribute, or NULL if no matching attribute found.
   *
   * @psalm-return T|null
   * @phpstan-return T|null
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   *   More than one attribute of the given type.
   */
  public static function getSingle(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty|FactoryReflectionInterface $reflector,
    string $name,
  ): ?object {
    $attribute = self::getSingleAttribute($reflector, $name);
    if ($attribute === NULL) {
      return NULL;
    }
    $instance = $attribute->newInstance();
    if ($instance instanceof ReflectorAwareAttributeInterface) {
      $instance->setReflector($reflector);
    }
    return $instance;
  }

  /**
   * Gets and instantiates the first attribute of a given type, if exists.
   *
   * @template T of object
   *
   * @param \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty|\Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface $reflector
   *   Element that has the attribute.
   * @param class-string<T> $name
   *   Expected attribute type/name.
   *
   * @return \ReflectionAttribute<T>|null
   *   Instance from the attribute, or NULL if no matching attribute found.
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   *   More than one attribute of the given type.
   */
  private static function getSingleAttribute(
    \ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionClassConstant|\ReflectionProperty|FactoryReflectionInterface $reflector,
    string $name,
  ): ?\ReflectionAttribute {
    /** @var \ReflectionAttribute<T>[] $attributes */
    $attributes = $reflector->getAttributes($name, \ReflectionAttribute::IS_INSTANCEOF);
    if (!$attributes) {
      return null;
    }
    if (\array_keys($attributes) !== [0]) {
      throw new MalformedDeclarationException(\sprintf(
        'More than one %s attribute found on %s.',
        $name,
        MessageUtil::formatReflector($reflector),
      ));
    }
    return $attributes[0];
  }

}
