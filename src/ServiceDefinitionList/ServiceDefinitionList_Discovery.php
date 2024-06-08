<?php

declare(strict_types = 1);

namespace Ock\DID\ServiceDefinitionList;

use Ock\ClassDiscovery\FactsIA\FactsIAInterface;

/**
 * @template-implements \Ock\DID\ServiceDefinitionList\ServiceDefinitionListInterface<false>
 */
class ServiceDefinitionList_Discovery implements ServiceDefinitionListInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\FactsIA\FactsIAInterface<\Ock\DID\ServiceDefinition\ServiceDefinition> $discovery
   */
  public function __construct(
    private readonly FactsIAInterface $discovery,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getDefinitions(): array {
    return \iterator_to_array($this->discovery, false);
  }

}
