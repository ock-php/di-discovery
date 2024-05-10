<?php

declare(strict_types = 1);

namespace Donquixote\DID\ContainerToValue;

use Psr\Container\ContainerInterface;

/**
 * Treats the service itself as a callable.
 */
class ContainerToValue_ObjectMethodCall extends ContainerToValue_ArgumentsBase {

  /**
   * Constructor.
   *
   * @param \Donquixote\DID\ContainerToValue\ContainerToValueInterface $containerToObject
   * @param string $method
   * @param (mixed|\Donquixote\DID\ContainerToValue\ContainerToValueInterface)[] $args
   */
  public function __construct(
    private readonly ContainerToValueInterface $containerToObject,
    private readonly string $method,
    array $args,
  ) {
    parent::__construct($args);
  }

  /**
   * {@inheritdoc}
   */
  protected function getWithArgs(ContainerInterface $container, array $args): mixed {
    $object = $this->containerToObject->containerGetValue($container);
    return $object->{$this->method}(...$args);
  }

}
