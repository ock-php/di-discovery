<?php

declare(strict_types = 1);

namespace Donquixote\DID\Tests\Fixtures;

class C {

  public array $values;

  public function __construct(
    mixed ...$args,
  ) {
    $this->values = $args;
  }

  public static function getClassName(): string {
    return GenericObject::class;
  }

  public static function getMethodName(): string {
    return 'someMethod';
  }

  /**
   * @throws \Exception
   */
  public static function create(...$args): mixed {
    return new GenericObject(
      class: self::class,
      method: 'create',
      args: $args,
    );
  }

  /**
   * @throws \Exception
   */
  public function foo(...$args): mixed {
    return new GenericObject(
      object: $this,
      method: 'foo',
      args: $args,
    );
  }

}
