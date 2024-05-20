<?php

declare(strict_types = 1);

namespace Ock\DID\EggList;

interface EggListInterface {

  /**
   * @return \Ock\Egg\Egg\EggInterface[]
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  public function getEggs(): array;

}
