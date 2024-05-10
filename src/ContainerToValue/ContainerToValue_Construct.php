<?php

declare(strict_types = 1);

namespace Donquixote\DID\ContainerToValue;

use Donquixote\DID\Exception\ContainerToValueException;
use Psr\Container\ContainerInterface;

/**
 * @template T
 *
 * @template-extends ContainerToValue_ArgumentsBase<T>
 */
class ContainerToValue_Construct extends ContainerToValue_ArgumentsBase {

  /**
   * Constructor.
   *
   * @param class-string<T> $class
   * @param (mixed|\Donquixote\DID\ContainerToValue\ContainerToValueInterface)[] $args
   */
  public function __construct(
    private readonly string $class,
    array $args,
  ) {
    parent::__construct($args);
  }

  /**
   * {@inheritdoc}
   */
  protected function getWithArgs(ContainerInterface $container, array $args): mixed {
    try {
      return new ($this->class)(...$args);
    }
    catch (\Throwable $e) {
      throw new ContainerToValueException($e->getMessage(), 0, $e);
    }
  }

}
