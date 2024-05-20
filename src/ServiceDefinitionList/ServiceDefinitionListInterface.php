<?php

declare(strict_types = 1);

namespace Ock\DID\ServiceDefinitionList;

/**
 * @template KeyedById as bool
 */
interface ServiceDefinitionListInterface {

  /**
   * @return \Ock\DID\ServiceDefinition\ServiceDefinition[]
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  public function getDefinitions(): array;

}
