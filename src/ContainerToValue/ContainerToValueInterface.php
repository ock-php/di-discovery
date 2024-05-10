<?php

declare(strict_types = 1);

namespace Donquixote\DID\ContainerToValue;

use Psr\Container\ContainerInterface;

/**
 * @template T
 */
interface ContainerToValueInterface {

  /**
   * @param \Psr\Container\ContainerInterface $container
   *
   * @return T
   *
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   */
  public function containerGetValue(ContainerInterface $container): mixed;

}
