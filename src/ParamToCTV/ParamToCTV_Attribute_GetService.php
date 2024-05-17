<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Donquixote\DID\Attribute\Parameter\GetServiceInterface;
use Ock\Egg\Egg\Egg_ServiceId;
use Ock\Egg\Egg\EggInterface;
use Donquixote\ClassDiscovery\Util\AttributesUtil;

class ParamToCTV_Attribute_GetService implements ParamToCTVInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?EggInterface {
    $attribute = AttributesUtil::getSingle($parameter, GetServiceInterface::class);
    if ($attribute === NULL) {
      return NULL;
    }
    $id = $attribute->paramGetServiceId($parameter);
    return new Egg_ServiceId($id);
  }

}
