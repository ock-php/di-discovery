<?php

declare(strict_types = 1);

namespace Donquixote\DID\ServiceDefinitionList;

/**
 * @template KeyedById as bool
 */
interface ServiceDefinitionListInterface {

  /**
   * @return \Donquixote\DID\ServiceDefinition\ServiceDefinition[]
   *
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  public function getDefinitions(): array;

}
