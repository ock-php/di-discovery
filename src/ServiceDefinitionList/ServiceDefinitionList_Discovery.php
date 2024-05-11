<?php

declare(strict_types = 1);

namespace Donquixote\DID\ServiceDefinitionList;

use Donquixote\ClassDiscovery\Discovery\DiscoveryInterface;

/**
 * @template-implements \Donquixote\DID\ServiceDefinitionList\ServiceDefinitionListInterface<false>
 */
class ServiceDefinitionList_Discovery implements ServiceDefinitionListInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\Discovery\DiscoveryInterface<\Donquixote\DID\ServiceDefinition\ServiceDefinition> $discovery
   */
  public function __construct(
    private readonly DiscoveryInterface $discovery,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getDefinitions(): array {
    return \iterator_to_array($this->discovery, false);
  }

}
