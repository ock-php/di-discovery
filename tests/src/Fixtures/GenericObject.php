<?php

declare(strict_types = 1);

namespace Donquixote\DID\Tests\Fixtures;

class GenericObject {

  public array $values;

  public function __construct(mixed ...$args) {
    $this->values = $args;
  }

  public static function __callStatic(string $name, array $arguments): self {
    return new self(
      method: '::' . $name,
      args: $arguments,
    );
  }

  public function __call(string $name, array $arguments): self {
    return new self(
      object: $this,
      method: $name,
      args: $arguments,
    );
  }

}
