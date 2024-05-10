<?php

declare(strict_types = 1);

namespace Donquixote\DID\ValueDefinition;

/**
 * Abstract interface with no methods.
 *
 * Every implementation will have its own methods.
 */
final class ValueDefinition_CallObjectMethod implements ValueDefinitionInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\DID\ValueDefinition\ValueDefinitionInterface $object
   * @param string|\Donquixote\DID\ValueDefinition\ValueDefinitionInterface $method
   * @param array<array-key, ValueDefinitionInterface|mixed> $args
   */
  public function __construct(
    public readonly ValueDefinitionInterface $object,
    public readonly string|ValueDefinitionInterface $method,
    public readonly array $args,
  ) {}

}
