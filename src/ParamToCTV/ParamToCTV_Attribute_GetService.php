<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Donquixote\DID\Attribute\Parameter\GetServiceInterface;
use Donquixote\DID\ContainerToValue\ContainerToValue_ServiceId;
use Donquixote\DID\ContainerToValue\ContainerToValueInterface;
use Donquixote\DID\Util\AttributesUtil;

class ParamToCTV_Attribute_GetService implements ParamToCTVInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?ContainerToValueInterface {
    $attribute = AttributesUtil::getSingle($parameter, GetServiceInterface::class);
    if ($attribute === NULL) {
      return NULL;
    }
    $id = $attribute->paramGetServiceId($parameter);
    return new ContainerToValue_ServiceId($id);
  }

}
