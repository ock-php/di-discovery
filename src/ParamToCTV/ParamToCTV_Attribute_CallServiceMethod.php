<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Donquixote\ClassDiscovery\Util\AttributesUtil;
use Donquixote\DID\Attribute\Parameter\CallServiceMethod;
use Ock\Egg\Egg\Egg_ObjectMethodCall;
use Ock\Egg\Egg\Egg_ServiceId;
use Ock\Egg\Egg\EggInterface;

class ParamToCTV_Attribute_CallServiceMethod implements ParamToCTVInterface {

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
