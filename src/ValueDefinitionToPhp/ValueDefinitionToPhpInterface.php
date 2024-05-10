<?php

declare(strict_types = 1);

namespace Donquixote\DID\ValueDefinitionToPhp;

use Donquixote\DID\ValueDefinition\ValueDefinitionInterface;

interface ValueDefinitionToPhpInterface {

  /**
   * @param \Donquixote\DID\ValueDefinition\ValueDefinitionInterface $definition
   *   Object that defines a value.
   * @param bool $enclose
   *   TRUE to enclose expressions that would otherwise cause ambiguity.
   *
   * @return string
   *   PHP expression that produces the value described in the definition.
   *
   * @throws \Donquixote\CodegenTools\Exception\CodegenException
   *   The value contains parts that cannot be exported.
   */
  public function generate(ValueDefinitionInterface $definition, bool $enclose = FALSE): string;

}
