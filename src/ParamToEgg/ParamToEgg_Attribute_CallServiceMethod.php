<?php

declare(strict_types = 1);

namespace Ock\DID\ParamToEgg;

use Ock\ClassDiscovery\Util\AttributesUtil;
use Ock\DID\Attribute\Parameter\CallServiceMethod;
use Ock\Egg\Egg\Egg_ObjectMethodCall;
use Ock\Egg\Egg\Egg_ServiceId;
use Ock\Egg\Egg\EggInterface;
use Ock\Egg\Exception\ToEggException;
use Ock\Egg\ParamToEgg\ParamToEggInterface;
use Ock\Helpers\Util\MessageUtil;
use Psr\Container\ContainerInterface;

class ParamToEgg_Attribute_CallServiceMethod implements ParamToEggInterface {

  public function __construct(
    private readonly ContainerInterface $container,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function paramGetEgg(\ReflectionParameter $parameter): ?EggInterface {
    $attribute = AttributesUtil::getSingle($parameter, CallServiceMethod::class);
    if ($attribute === NULL) {
      return NULL;
    }
    $id = $attribute->serviceId;
    if (!$this->container->has($id)) {
      throw new ToEggException(sprintf(
        'Service %s for %s does not exist.',
        \var_export($id, true),
        MessageUtil::formatReflector($parameter),
      ));
    }
    return new Egg_ObjectMethodCall(
      new Egg_ServiceId($id),
      $attribute->method,
      $attribute->args,
    );
  }

}
