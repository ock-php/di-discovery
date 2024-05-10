<?php
declare(strict_types=1);

namespace Donquixote\DID\Util;

use Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Donquixote\CodegenTools\Util\MessageUtil;
use Donquixote\DID\Exception\MalformedDeclarationException;

class ReflectionTypeUtil {

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract|\ReflectionClass|FactoryReflectionInterface $reflector
   * @param bool $allowObject
   *   TRUE, to allow 'object' return type.
   *
   * @return class-string|null
   * @psalm-return ($allowObject is true ? (class-string|null) : class-string)
   * @phpstan-return ($allowObject is true ? (class-string|null) : class-string)
   *   The type, or NULL for 'object'.
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   *   The return type does not match the expectation.
   */
  public static function requireGetClassLikeType(
    \ReflectionParameter|\ReflectionFunctionAbstract|\ReflectionClass|FactoryReflectionInterface $reflector,
    bool $allowObject = false,
  ): ?string {
    $type = self::readType($reflector);
    if ($type !== false) {
      if ($type !== true) {
        return $type;
      }
      if ($allowObject) {
        return null;
      }
    }
    throw new MalformedDeclarationException(\sprintf(
      'Expected a class-like %s declaration on %s.',
      $reflector instanceof \ReflectionParameter ? 'type' : 'return type',
      MessageUtil::formatReflector($reflector),
    ));
  }

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract|\ReflectionClass|FactoryReflectionInterface $reflector
   *
   * @return class-string|null
   */
  public static function getClassLikeType(
    \ReflectionParameter|\ReflectionFunctionAbstract|\ReflectionClass|FactoryReflectionInterface $reflector,
  ): ?string {
    $type = self::readType($reflector);
    return match ($type) {
      true, false => null,
      default => $type,
    };
  }

  /**
   * Gets the return value class name, if it is unique.
   *
   * @return class-string|bool
   *   If this is a class, the class name is returned.
   *   If this is a method:
   *     - A class name, if the declared return type is a single class name.
   *     - TRUE, if the single declared return type is 'object'.
   *     - FALSE, if the declared type is not a single class name.
   */
  protected static function readType(
    \ReflectionParameter|\ReflectionFunctionAbstract|\ReflectionClass|FactoryReflectionInterface $reflector,
  ): string|bool {
    if ($reflector instanceof \ReflectionClass) {
      return $reflector->getName();
    }
    if ($reflector instanceof \ReflectionParameter) {
      $type = $reflector->getType();
    }
    else {
      $type = $reflector->getReturnType();
    }
    if (!$type instanceof \ReflectionNamedType) {
      return false;
    }
    $name = $type->getName();
    if ($type->isBuiltin()) {
      if ($name === 'object') {
        return true;
      }
      return false;
    }
    if ($name === 'self' || $name === 'static') {
      $reflectionFunction = $reflector instanceof \ReflectionParameter
        ? $reflector->getDeclaringFunction()
        : $reflector;
      if (!$reflectionFunction instanceof \ReflectionMethod) {
        throw new \RuntimeException(\sprintf(
          'Unexpected %s outside class context, in type declaration for %s.',
          "'$name'",
          MessageUtil::formatReflector($reflector),
        ));
      }
      $name = $reflectionFunction->getDeclaringClass()->getName();
    }
    return $name;
  }

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract $reflector
   *
   * @return string|null
   *   Name of a built-in type, or null.
   */
  public static function getBuiltinType(
    \ReflectionParameter|\ReflectionFunctionAbstract $reflector,
  ): ?string {
    $type = $reflector instanceof \ReflectionParameter
      ? $reflector->getType()
      : $reflector->getReturnType();
    if (!$type instanceof \ReflectionNamedType || !$type->isBuiltin()) {
      return null;
    }
    return $type->getName();
  }

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract $reflector
   * @param class-string|null $expected
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   */
  public static function requireClassLikeType(
    \ReflectionParameter|\ReflectionFunctionAbstract $reflector,
    ?string $expected,
  ): void {
    $name = self::requireGetClassLikeType($reflector);
    if ($name !== $expected) {
      throw new MalformedDeclarationException(\sprintf(
        'Expected a %s type declaration on %s.',
        $expected,
        MessageUtil::formatReflector($reflector),
      ));
    }
  }

  /**
   * @param \ReflectionParameter|\ReflectionFunctionAbstract $reflector
   * @param string $expected
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   */
  public static function requireBuiltinType(
    \ReflectionParameter|\ReflectionFunctionAbstract $reflector,
    string $expected,
  ): void {
    $type = $reflector instanceof \ReflectionParameter
      ? $reflector->getType()
      : $reflector->getReturnType();
    if (!$type instanceof \ReflectionNamedType || !$type->isBuiltin() || $type->getName() !== $expected) {
      throw new MalformedDeclarationException(\sprintf(
        'Expected a %s type declaration on %s.',
        $expected,
        MessageUtil::formatReflector($reflector),
      ));
    }
  }

}
