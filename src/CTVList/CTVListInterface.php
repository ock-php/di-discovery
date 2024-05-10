<?php

declare(strict_types = 1);

namespace Donquixote\DID\CTVList;

interface CTVListInterface {

  /**
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface[]
   *
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  public function getCTVs(): array;

}
