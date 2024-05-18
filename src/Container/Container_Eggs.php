<?php

declare(strict_types = 1);

namespace Donquixote\DID\Container;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\DID\EggList\EggList_Discovery_ServiceAttribute;
use Donquixote\DID\Exception\ContainerToValueException;
use Ock\Egg\Egg\Egg_Container;
use Donquixote\DID\ParamToEgg\ParamToEgg;
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
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface[] $classFilesIAs
   *
   * @return \Psr\Container\ContainerInterface
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
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
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   */
  public function get(string $id): mixed {
    return $this->cache[$id]
      ??= ($this->eggs[$id] ?? $this->fail($id))->hatch($this);
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
    return isset($this->eggs[$id]);
  }

}
