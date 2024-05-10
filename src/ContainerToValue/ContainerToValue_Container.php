<?php

declare(strict_types = 1);

namespace Donquixote\DID\ContainerToValue;

use Psr\Container\ContainerInterface;

class ContainerToValue_Container implements ContainerToValueInterface {

  /**
   * {@inheritdoc}
   */
  public function containerGetValue(ContainerInterface $container): ContainerInterface {
    return $container;
  }

}
