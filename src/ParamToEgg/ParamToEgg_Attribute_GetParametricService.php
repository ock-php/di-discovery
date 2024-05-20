<?php

declare(strict_types = 1);

namespace Ock\DID\ParamToEgg;

use Ock\ClassDiscovery\Util\AttributesUtil;
use Ock\DID\Attribute\Parameter\GetParametricService;
use Ock\Egg\Egg\Egg_CallableCall;
use Ock\Egg\Egg\Egg_ServiceId;
use Ock\Egg\Egg\EggInterface;
use Ock\Egg\ParamToEgg\ParamToEggInterface;

class ParamToEgg_Attribute_GetParametricService implements ParamToEggInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetEgg(\ReflectionParameter $parameter): ?EggInterface {
    $attribute = AttributesUtil::getSingle($parameter, GetParametricService::class);
    if ($attribute === NULL) {
      return NULL;
    }
    $id = $attribute->paramGetServiceId($parameter);
    return new Egg_CallableCall(
      new Egg_ServiceId($id),
      $attribute->args,
    );
  }

}
