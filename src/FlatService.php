<?php

declare(strict_types = 1);

namespace Donquixote\DID;

class FlatService {

  public static function get(mixed $value): mixed {
    return $value;
  }

  public static function construct(string $class, int $n, ...$args): object {
    $args = self::unpackArgs($n, $args);
    return new $class(...$args);
  }

  public static function constructDynamic(int $n, ...$args): object {
    $args = self::unpackArgs($n, $args);
    $class = array_shift($args);
    return new $class(...$args);
  }

  public static function call(callable $callback, int $n, ...$args): mixed {
    $args = self::unpackArgs($n, $args);
    return $callback(...$args);
  }

  public static function callDynamic(int $n, ...$args): mixed {
    $args = self::unpackArgs($n, $args);
    $callback = array_shift($args);
    return $callback(...$args);
  }

  public static function method(object $object, string $method, int $n, ...$args): object {
    $args = self::unpackArgs($n, $args);
    return $object->$method(...$args);
  }

  public static function parametric(...$serviceArgs): mixed {
    return static function (...$callArgs) use ($serviceArgs) {
      foreach ($serviceArgs as &$serviceArg) {
        if (is_array($serviceArg) && ($serviceArg['op'] ?? NULL) === 'arg') {
          $serviceArg = ['op' => 'value', 'value' => $callArgs[$serviceArg['position']]];
        }
      }
      return self::unpackArgs(1, $serviceArgs)[0];
    };
  }

  /**
   * @param int $n
   * @param array $args
   *
   * @return array
   */
  public static function unpackArgs(int $n, array $args): array {
    $lookup = static function (int $i) use (&$args): mixed {
      return $args[$i];
    };
    for ($i = count($args) - 1; $i >= 0; --$i) {
      if (is_array($args[$i])) {
        $args[$i] = self::unpackArg($args[$i], $lookup);
      }
    }
    return array_slice($args, 0, $n);
  }

  /**
   * @param array $arg
   * @param callable(int): mixed $lookup
   *
   * @return mixed
   */
  private static function unpackArg(array $arg, callable $lookup): mixed {
    /** @noinspection PhpVoidFunctionResultUsedInspection */
    return match ($arg['op'] ?? NULL) {
      'value' => $arg['value'],
      'array' => array_map($lookup, $arg['array']),
      'call' => $lookup($arg['callback'])(
        ...array_map($lookup, $arg['args']),
      ),
      'construct' => new ($lookup($arg['class']))(
        ...array_map($lookup, $arg['args']),
      ),
      'arg' => self::failUnpack('Unexpected parametric value in non-parametric definition.'),
      NULL => $arg,
      default => self::failUnpack(sprintf('Unexpected operation %s.', $arg['op'])),
    };
  }

  private static function failUnpack(string $message): never {
    throw new \RuntimeException($message);
  }

}
