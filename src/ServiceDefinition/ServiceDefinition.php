<?php

declare(strict_types = 1);

namespace Donquixote\DID\ServiceDefinition;

use Donquixote\DID\ValueDefinition\ValueDefinitionInterface;

class ServiceDefinition {

  public function __construct(
    public readonly string $id,
    public readonly string $class,
    public readonly ValueDefinitionInterface $valueDefinition,
  ) {}

}
