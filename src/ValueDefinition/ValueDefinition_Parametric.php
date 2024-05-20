<?php

declare(strict_types = 1);

namespace Ock\DID\ValueDefinition;

class ValueDefinition_Parametric implements ValueDefinitionInterface {

  /**
   * Constructor.
   *
   * @param mixed|\Ock\DID\ValueDefinition\ValueDefinitionInterface $value
   *   Value definition that may contain free arguments.
   *
   * @see \Ock\DID\ValueDefinition\ValueDefinition_GetArgument
   */
  public function __construct(
    public readonly mixed $value,
  ) {}

}
