<?php

declare(strict_types = 1);

namespace Donquixote\DID\ClassToCTV;

use Ock\Egg\Egg\EggInterface;

interface ClassToCTVInterface {

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Ock\Egg\Egg\EggInterface
   *
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  public function classGetCTV(\ReflectionClass $reflectionClass): EggInterface;

}
