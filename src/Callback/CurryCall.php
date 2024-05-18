<?php

declare(strict_types = 1);

namespace Donquixote\DID\Callback;

use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;
use Donquixote\DID\ValueDefinition\ValueDefinitionInterface;
use Ock\Egg\Egg\Egg_Construct;
use Ock\Egg\Egg\EggInterface;

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
  ): ValueDefinitionInterface {
    return new ValueDefinition_Construct(self::class, [
      $callbackDefinition,
      $namedArgDefinitions,
      $curryArgsMap,
      $callableArgDefinitions,
    ]);
  }

  /**
   * Creates an egg for this class.
   *
   * @template TT as mixed
   *
   * @param \Ock\Egg\Egg\EggInterface<TT>|(callable(): TT) $callbackOrEgg
   * @param list<\Ock\Egg\Egg\EggInterface|mixed> $namedArgEggs
   * @param array<string, int> $curryArgsMap
   * @param array<string, EggInterface> $callableArgEggs
   *
   * @return \Ock\Egg\Egg\EggInterface<self<TT>>
   */
  public static function egg(
    EggInterface|callable $callbackOrEgg,
    array $namedArgEggs,
    array $curryArgsMap = [],
    array $callableArgEggs = [],
  ): EggInterface {
    return new Egg_Construct(self::class, [
      $callbackOrEgg,
      $namedArgEggs,
      $curryArgsMap,
      $callableArgEggs,
    ]);
  }

  /**
   * @param class-string|\Ock\Egg\Egg\EggInterface $classOrEgg
   * @param string $method
   * @param list<\Ock\Egg\Egg\EggInterface|mixed> $namedArgEggs
   * @param array<string, int> $curryArgsMap
   * @param array<string, EggInterface> $callableArgEggs
   *
   * @return \Ock\Egg\Egg\EggInterface<self>
   */
  public static function eggMethodCall(
    string|EggInterface $classOrEgg,
    string $method,
    array $namedArgEggs,
    array $curryArgsMap = [],
    array $callableArgEggs = [],
  ): EggInterface {
    return new Egg_Construct(self::class, [
      [$classOrEgg, $method],
      $namedArgEggs,
      $curryArgsMap,
      $callableArgEggs,
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
