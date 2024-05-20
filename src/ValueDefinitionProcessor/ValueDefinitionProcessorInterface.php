<?php

declare(strict_types = 1);

namespace Ock\DID\ValueDefinitionProcessor;

use Ock\DID\ValueDefinition\ValueDefinitionInterface;

interface ValueDefinitionProcessorInterface {

  /**
   * @param \Ock\DID\ValueDefinition\ValueDefinitionInterface $definition
   *
   * @return \Ock\DID\ValueDefinition\ValueDefinitionInterface
   */
  public function process(ValueDefinitionInterface $definition): ValueDefinitionInterface;

}
