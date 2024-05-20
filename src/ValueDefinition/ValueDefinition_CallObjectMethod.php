<?php

declare(strict_types = 1);

namespace Ock\DID\ValueDefinition;

/**
 * Abstract interface with no methods.
 *
 * Every implementation will have its own methods.
 */
final class ValueDefinition_CallObjectMethod implements ValueDefinitionInterface {

  /**
   * Constructor.
   *
   * @param \Ock\DID\ValueDefinition\ValueDefinitionInterface $object
   * @param string|\Ock\DID\ValueDefinition\ValueDefinitionInterface $method
   * @param array<array-key, ValueDefinitionInterface|mixed> $args
   */
  public function __construct(
    public readonly ValueDefinitionInterface $object,
    public readonly string|ValueDefinitionInterface $method,
    public readonly array $args,
  ) {}

}
