<?php

declare(strict_types = 1);

namespace Ock\DID\ValueDefinition;

/**
 * Abstract interface with no methods.
 *
 * Every implementation will have its own methods.
 */
final class ValueDefinition_GetArgument implements ValueDefinitionInterface {

  /**
   * Constructor.
   *
   * @param int $position
   */
  public function __construct(
    public readonly int $position = 0,
  ) {}

}
