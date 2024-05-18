<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToEgg;

use Donquixote\ClassDiscovery\Util\AttributesUtil;
use Donquixote\DID\Attribute\Parameter\CallServiceMethod;
use Ock\Egg\Egg\Egg_ObjectMethodCall;
use Ock\Egg\Egg\Egg_ServiceId;
use Ock\Egg\Egg\EggInterface;
use Ock\Egg\ParamToEgg\ParamToEggInterface;

class ParamToEgg_Attribute_CallServiceMethod implements ParamToEggInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?EggInterface {
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
