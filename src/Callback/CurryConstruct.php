<?php

declare(strict_types = 1);

namespace Donquixote\DID\Callback;

use Donquixote\DID\ContainerToValue\ContainerToValue_Construct;
use Donquixote\DID\ContainerToValue\ContainerToValueInterface;

/**
 * @template T as object
 */
class CurryConstruct {

  /**
   * Constructor.
   *
   * @param class-string<T> $class
   * @param list<mixed> $args
   * @param array<int, int> $curryArgNames
   * @param array<int, callable> $callableArgs
   */
  public function __construct(
    private readonly string $class,
    private readonly array $args,
    private readonly array $curryArgNames = [],
    private readonly array $callableArgs = [],
  ) {}

  /**
   * Creates the CTV object.
   *
   * @template T as object
   *
   * @param class-string<T> $class
   * @param list<\Donquixote\DID\ContainerToValue\ContainerToValueInterface|mixed> $namedArgCTVs
   * @param list<string> $curryArgNames
   * @param array $callableArgCTVs
   *
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface<self<T>>
   */
  public static function ctv(
    string $class,
    array $namedArgCTVs,
    array $curryArgNames = [],
    array $callableArgCTVs = [],
  ): ContainerToValueInterface {
    return new ContainerToValue_Construct(self::class, [
      $class,
      $namedArgCTVs,
      $curryArgNames,
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
    $constructorArgs = $this->args;
    foreach ($this->curryArgNames as $position => $name) {
      $constructorArgs[$name] = $args[$position];
    }
    foreach ($this->callableArgs as $name => $callable) {
      $constructorArgs[$name] = $callable(...$args);
    }
    return new ($this->class)(...$constructorArgs);
  }

}
