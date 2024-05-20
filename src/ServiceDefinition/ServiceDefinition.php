<?php

declare(strict_types = 1);

namespace Ock\DID\ServiceDefinition;

use Ock\DID\ValueDefinition\ValueDefinitionInterface;

class ServiceDefinition {

  public function __construct(
    public readonly string $id,
    public readonly string $class,
    public readonly ValueDefinitionInterface $valueDefinition,
  ) {}

}
