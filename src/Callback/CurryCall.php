<?php

declare(strict_types = 1);

namespace Donquixote\DID\Callback;

use Donquixote\DID\ContainerToValue\ContainerToValue_Construct;
use Donquixote\DID\ContainerToValue\ContainerToValueInterface;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;
use Donquixote\DID\ValueDefinition\ValueDefinitionInterface;

/**
 * @template T as mixed
 */
class CurryCall {

  /**
   * Constructor.
   *
   * @param callable(): T $callback
   * @param array<string, mixed> $namedArgs
   * @param list<string> $curryArgNames
   * @param array $callableArgs
   */
  public function __construct(
    private readonly mixed $callback,
    private readonly array $namedArgs,
    private readonly array $curryArgNames = [],
    private readonly array $callableArgs = [],
  ) {}

  public static function vd(
    ValueDefinitionInterface|callable $callbackDefinition,
    array $argDefinitions,
  ) {
    $namedArgDefinitions = [];
    $curryArgNames = [];
    $callableArgDefinitions = [];
    foreach ($argDefinitions as $i => $argDefinition) {

    }
  }

  private static function processArgDefinition(mixed $argDefinition): ?ValueDefinitionInterface {

  }

  public static function valueDefinition(
    ValueDefinitionInterface|callable $callbackDefinition,
    array $namedArgDefinitions,
    array $curryArgsMap = [],
    array $callableArgDefinitions = [],
  ) {
    return new ValueDefinition_Construct(self::class, [
      $callbackDefinition,
      $namedArgDefinitions,
      $curryArgsMap,
      $callableArgDefinitions,
    ]);
  }

  /**
   * Creates the CTV object.
   *
   * @template T as mixed
   *
   * @param \Donquixote\DID\ContainerToValue\ContainerToValueInterface<T>|(callable(): T) $callbackCTV
   * @param list<\Donquixote\DID\ContainerToValue\ContainerToValueInterface|mixed> $namedArgCTVs
   * @param array<string, int> $curryArgsMap
   * @param array<string, ContainerToValueInterface> $callableArgCTVs
   *
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface<self<T>>
   */
  public static function ctv(
    ContainerToValueInterface|callable $callbackCTV,
    array $namedArgCTVs,
    array $curryArgsMap = [],
    array $callableArgCTVs = [],
  ): ContainerToValueInterface {
    return new ContainerToValue_Construct(self::class, [
      $callbackCTV,
      $namedArgCTVs,
      $curryArgsMap,
      $callableArgCTVs,
    ]);
  }

  /**
   * @param class-string|\Donquixote\DID\ContainerToValue\ContainerToValueInterface $classOrObjectCTV
   * @param string $method
   * @param list<\Donquixote\DID\ContainerToValue\ContainerToValueInterface|mixed> $namedArgCTVs
   * @param array<string, int> $curryArgsMap
   * @param array<string, ContainerToValueInterface> $callableArgCTVs
   *
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface<self>
   */
  public static function ctvMethodCall(
    string|ContainerToValueInterface $classOrObjectCTV,
    string $method,
    array $namedArgCTVs,
    array $curryArgsMap = [],
    array $callableArgCTVs = [],
  ): ContainerToValueInterface {
    return new ContainerToValue_Construct(self::class, [
      [$classOrObjectCTV, $method],
      $namedArgCTVs,
      $curryArgsMap,
      $callableArgCTVs,
    ]);
  }

  /**
   * The method that makes this a callable.
   *
   * @param mixed ...$args
   *
   * @return T
   */
  public function __invoke(mixed ...$args): object {
    $constructorArgs = $this->namedArgs;
    foreach ($this->curryArgNames as $position => $name) {
      $constructorArgs[$name] = $args[$position];
    }
    foreach ($this->callableArgs as $name => $callable) {
      $constructorArgs[$name] = $callable(...$args);
    }
    return ($this->callback)(...$constructorArgs);
  }

}
