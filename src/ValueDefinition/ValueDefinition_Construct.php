<?php

declare(strict_types = 1);

namespace Ock\DID\ValueDefinition;

/**
 * Abstract interface with no methods.
 *
 * Every implementation will have its own methods.
 */
final class ValueDefinition_Construct implements ValueDefinitionInterface {

  /**
   * Constructor.
   *
   * @param class-string|ValueDefinitionInterface $class
   * @param array<array-key, ValueDefinitionInterface|mixed> $args
   */
  public function __construct(
    public readonly string|ValueDefinitionInterface $class,
    public readonly array $args = [],
  ) {}

}
