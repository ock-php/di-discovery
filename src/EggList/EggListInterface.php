<?php

declare(strict_types = 1);

namespace Donquixote\DID\EggList;

interface EggListInterface {

  /**
   * @return \Ock\Egg\Egg\EggInterface[]
   *
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  public function getCTVs(): array;

}
