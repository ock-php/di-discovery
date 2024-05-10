<?php

declare(strict_types = 1);

namespace Donquixote\DID\ContainerToValue;

use Donquixote\DID\Exception\ContainerToValueException;
use Psr\Container\ContainerInterface;

class ContainerToValue_CallableCall extends ContainerToValue_ArgumentsBase {

  /**
   * Constructor.
   *
   * @param \Donquixote\DID\ContainerToValue\ContainerToValueInterface $containerToCallback
   * @param (mixed|\Donquixote\DID\ContainerToValue\ContainerToValueInterface)[] $args
   */
  public function __construct(
    private readonly ContainerToValueInterface $containerToCallback,
    array $args,
  ) {
    parent::__construct($args);
  }

  /**
   * @param callable $callback
   * @param (mixed|\Donquixote\DID\ContainerToValue\ContainerToValueInterface)[] $args
   *
   * @return self
   */
  public static function createFixed(callable $callback, array $args): self {
    return new self(
      new ContainerToValue_Fixed($callback),
      $args,
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getWithArgs(ContainerInterface $container, array $args): mixed {
    $callback = $this->containerToCallback->containerGetValue($container);
    try {
      return $callback(...$args);
    }
    catch (\Throwable $e) {
      throw new ContainerToValueException($e->getMessage(), 0, $e);
    }
  }

}
