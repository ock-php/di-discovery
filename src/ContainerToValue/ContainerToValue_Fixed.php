<?php

declare(strict_types = 1);

namespace Donquixote\DID\ContainerToValue;

use Psr\Container\ContainerInterface;

class ContainerToValue_Fixed implements ContainerToValueInterface {

  /**
   * Constructor.
   *
   * @param mixed $value
   */
  public function __construct(
    private readonly mixed $value,
  ) {}

  public function containerGetValue(ContainerInterface $container): mixed {
    return $this->value;
  }

}
