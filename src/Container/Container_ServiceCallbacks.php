<?php

declare(strict_types = 1);

namespace Donquixote\DID\Container;

use Donquixote\DID\Exception\ContainerToValueException;
use Donquixote\DID\ServiceDefinitionList\ServiceDefinitionListInterface;
use Psr\Container\ContainerInterface;

class Container_ServiceCallbacks implements ContainerInterface {

  /**
   * @var array
   */
  private array $cache = [];

  /**
   * Constructor.
   *
   * @param array<string, callable(ContainerInterface): mixed> $callbacks
   */
  public function __construct(
    private readonly array $callbacks,
  ) {}

  /**
   * @param \Donquixote\DID\ServiceDefinitionList\ServiceDefinitionListInterface $serviceDefinitionList
   *
   * @return static
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  public static function fromServiceDefinitionList(ServiceDefinitionListInterface $serviceDefinitionList): self {
    $definitions = [];
    foreach ($serviceDefinitionList->getDefinitions() as $serviceDefinition) {
      $definitions[$serviceDefinition->id] = $serviceDefinition->valueDefinition;
    }
    return new self($definitions);
  }

  /**
   * @template T
   *
   * @param class-string<T>|string $id
   *
   * @return T|mixed
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   */
  public function get(string $id): mixed {
    return $this->cache[$id]
      ??= ($this->callbacks[$id] ?? $this->fail($id))($this);
  }

  /**
   * @param string $id
   *
   * @return never
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   */
  private function fail(string $id): never {
    throw new ContainerToValueException(sprintf(
      'Cannot retrieve service %s.',
      $id,
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function has(string $id): bool {
    return isset($this->callbacks[$id]);
  }

}
