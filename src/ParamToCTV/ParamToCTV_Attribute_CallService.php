<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Donquixote\DID\Attribute\Parameter\CallService;
use Donquixote\DID\ContainerToValue\ContainerToValue_CallableCall;
use Donquixote\DID\ContainerToValue\ContainerToValue_ServiceId;
use Donquixote\DID\ContainerToValue\ContainerToValueInterface;
use Donquixote\DID\Util\AttributesUtil;

class ParamToCTV_Attribute_CallService implements ParamToCTVInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?ContainerToValueInterface {
    $attribute = AttributesUtil::getSingle($parameter, CallService::class);
    if ($attribute === NULL) {
      return NULL;
    }
    $id = $attribute->paramGetServiceId($parameter);
    return new ContainerToValue_CallableCall(
      new ContainerToValue_ServiceId($id),
      $attribute->args,
    );
  }

}
