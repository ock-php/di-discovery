<?php

declare(strict_types = 1);

namespace Donquixote\DID\ContainerToValue;

use Psr\Container\ContainerInterface;

class ContainerToValue_Array extends ContainerToValue_ArgumentsBase {

  /**
   * Constructor.
   *
   * @param (mixed|\Donquixote\DID\ContainerToValue\ContainerToValueInterface)[] $args
   */
  public function __construct(
    array $args,
  ) {
    parent::__construct($args);
  }

  /**
   * {@inheritdoc}
   */
  protected function getWithArgs(ContainerInterface $container, array $args): array {
    return $args;
  }

}
