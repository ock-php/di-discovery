<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Donquixote\DID\Attribute\Parameter\GetParametricService;
use Ock\Egg\Egg\Egg_CallableCall;
use Ock\Egg\Egg\Egg_ServiceId;
use Ock\Egg\Egg\EggInterface;
use Donquixote\ClassDiscovery\Util\AttributesUtil;

class ParamToCTV_Attribute_GetParametricService implements ParamToCTVInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?EggInterface {
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
