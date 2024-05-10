<?php

declare(strict_types = 1);

namespace Donquixote\DID\ClassToCTV;

use Donquixote\DID\ContainerToValue\ContainerToValueInterface;

interface ClassToCTVInterface {

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface
   *
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  public function classGetCTV(\ReflectionClass $reflectionClass): ContainerToValueInterface;

}
