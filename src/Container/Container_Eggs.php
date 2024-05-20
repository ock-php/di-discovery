<?php

declare(strict_types = 1);

namespace Ock\DID\Container;

use Ock\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Ock\DID\EggList\EggList_Discovery_ServiceAttribute;
use Ock\DID\Exception\ContainerToValueException;
use Ock\DID\ParamToEgg\ParamToEgg;
use Ock\Egg\Egg\Egg_Container;
use Psr\Container\ContainerInterface;

class Container_Eggs implements ContainerInterface {

  /**
   * @var array
   */
  private array $cache = [];

  /**
   * Constructor.
   *
   * @param \Ock\Egg\Egg\EggInterface[] $eggs
   */
  public function __construct(
    private readonly array $eggs,
  ) {}

  /**
   * @param \Ock\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface[] $classFilesIAs
   *
   * @return \Psr\Container\ContainerInterface
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  public static function fromClassFilesIAs(array $classFilesIAs): ContainerInterface {
    $containerDiscoveryClassFilesIA = ClassFilesIA::multiple($classFilesIAs);
    $emptyEggList = new EggList_Discovery_ServiceAttribute(
      ParamToEgg::create(),
    );
    $eggs = $emptyEggList
      ->withClassFilesIA($containerDiscoveryClassFilesIA)
      ->getEggs();
    $eggs[ContainerInterface::class] = new Egg_Container();
    return new Container_Eggs($eggs);
  }

  /**
   * @template T
   *
   * @param class-string<T>|string $id
   *
   * @return T|mixed
   * @throws \Psr\Container\ContainerExceptionInterface
   */
  public function get(string $id): mixed {
    return $this->cache[$id]
      ??= ($this->eggs[$id] ?? $this->fail($id))->hatch($this);
  }

  /**
   * @param string $id
   *
   * @return never
   * @throws \Ock\DID\Exception\ContainerToValueException
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
    return isset($this->eggs[$id]);
  }

}
