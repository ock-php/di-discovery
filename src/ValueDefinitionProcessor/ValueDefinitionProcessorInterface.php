<?php

declare(strict_types = 1);

namespace Donquixote\DID\ValueDefinitionProcessor;

use Donquixote\DID\ValueDefinition\ValueDefinitionInterface;

interface ValueDefinitionProcessorInterface {

  /**
   * @param \Donquixote\DID\ValueDefinition\ValueDefinitionInterface $definition
   *
   * @return \Donquixote\DID\ValueDefinition\ValueDefinitionInterface
   */
  public function process(ValueDefinitionInterface $definition): ValueDefinitionInterface;

}
