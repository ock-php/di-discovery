<?php

declare(strict_types = 1);

namespace Donquixote\DID\ContainerToValue;

use Donquixote\DID\Exception\ContainerToValueException;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class ContainerToValue_ServiceId implements ContainerToValueInterface {

  /**
   * Constructor.
   *
   * @param string $serviceId
   */
  public function __construct(
    private readonly string $serviceId,
  ) {}

  /**
   * @param \Donquixote\DID\ValueDefinition\ValueDefinition_GetService $definition
   *
   * @return self
   */
  public static function fromValueDefinition(ValueDefinition_GetService $definition): self {
    return new self($definition->id);
  }

  /**
   * {@inheritdoc}
   */
  public function containerGetValue(ContainerInterface $container): mixed {
    try {
      return $container->get($this->serviceId);
    }
    catch (ContainerExceptionInterface $e) {
      throw new ContainerToValueException($e->getMessage(), 0, $e);
    }
  }

}
