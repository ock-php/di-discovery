<?php

declare(strict_types = 1);

namespace Ock\DID\ParamToEgg;

use Ock\ClassDiscovery\Util\AttributesUtil;
use Ock\DependencyInjection\Attribute\ServiceTag;
use Ock\DID\Attribute\Parameter\GetServiceInterface;
use Ock\Egg\Egg\Egg_ServiceId;
use Ock\Egg\Egg\EggInterface;
use Ock\Egg\Exception\ToEggException;
use Ock\Egg\ParamToEgg\ParamToEggInterface;
use Ock\Helpers\Util\MessageUtil;
use Psr\Container\ContainerInterface;

#[ServiceTag(self::SERVICE_TAG)]
class ParamToEgg_Attribute_GetService implements ParamToEggInterface {

  public function __construct(
    private readonly ContainerInterface $container,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function paramGetEgg(\ReflectionParameter $parameter): ?EggInterface {
    $attribute = AttributesUtil::getSingle($parameter, GetServiceInterface::class);
    if ($attribute === NULL) {
      return NULL;
    }
    $id = $attribute->paramGetServiceId($parameter);
    if (!$this->container->has($id)) {
      throw new ToEggException(sprintf(
        'Service %s for %s does not exist.',
        \var_export($id, true),
        MessageUtil::formatReflector($parameter),
      ));
    }
    return new Egg_ServiceId($id);
  }

}
