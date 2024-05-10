<?php

declare(strict_types = 1);

namespace Donquixote\DID\Container;

use Donquixote\DID\Exception\ServiceNotFoundException;
use Psr\Container\ContainerInterface;

class Container_Empty implements ContainerInterface {

  /**
   * {@inheritdoc}
   */
  public function get(string $id): never {
    throw new ServiceNotFoundException("Service '$id' not found in empty container.");
  }

  /**
   * {@inheritdoc}
   */
  public function has(string $id): bool {
    return false;
  }

}
