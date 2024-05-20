<?php

declare(strict_types = 1);

namespace Ock\DID\ParamToEgg;

use Ock\ClassDiscovery\Util\AttributesUtil;
use Ock\DID\Attribute\Parameter\CallServiceMethod;
use Ock\Egg\Egg\Egg_ObjectMethodCall;
use Ock\Egg\Egg\Egg_ServiceId;
use Ock\Egg\Egg\EggInterface;
use Ock\Egg\ParamToEgg\ParamToEggInterface;

class ParamToEgg_Attribute_CallServiceMethod implements ParamToEggInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetEgg(\ReflectionParameter $parameter): ?EggInterface {
    $attribute = AttributesUtil::getSingle($parameter, CallServiceMethod::class);
    if ($attribute === NULL) {
      return NULL;
    }
    return new Egg_ObjectMethodCall(
      new Egg_ServiceId($attribute->serviceId),
      $attribute->method,
      $attribute->args,
    );
  }

}
