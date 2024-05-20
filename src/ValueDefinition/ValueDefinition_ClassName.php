<?php

declare(strict_types = 1);

namespace Ock\DID\ValueDefinition;

/**
 * Abstract interface with no methods.
 *
 * Every implementation will have its own methods.
 */
final class ValueDefinition_ClassName implements ValueDefinitionInterface {

  /**
   * Constructor.
   *
   * @param class-string $class
   */
  public function __construct(
    public readonly string $class,
  ) {}

}
