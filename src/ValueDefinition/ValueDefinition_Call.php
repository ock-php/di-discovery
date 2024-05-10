<?php

declare(strict_types = 1);

namespace Donquixote\DID\ValueDefinition;

/**
 * Abstract interface with no methods.
 *
 * Every implementation will have its own methods.
 */
final class ValueDefinition_Call implements ValueDefinitionInterface {

  /**
   * Constructor.
   *
   * @param ValueDefinitionInterface|callable $callback
   * @param array<array-key, ValueDefinitionInterface|mixed> $args
   */
  public function __construct(
    public readonly mixed $callback,
    public readonly array $args = [],
  ) {}

}
