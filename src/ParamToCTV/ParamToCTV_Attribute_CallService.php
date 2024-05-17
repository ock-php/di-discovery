<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Donquixote\DID\Attribute\Parameter\CallService;
use Ock\Egg\Egg\Egg_CallableCall;
use Ock\Egg\Egg\Egg_ServiceId;
use Ock\Egg\Egg\EggInterface;
use Donquixote\ClassDiscovery\Util\AttributesUtil;

class ParamToCTV_Attribute_CallService implements ParamToCTVInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?EggInterface {
    $attribute = AttributesUtil::getSingle($parameter, CallService::class);
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
