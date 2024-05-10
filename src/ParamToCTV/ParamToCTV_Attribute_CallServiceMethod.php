<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Donquixote\DID\Attribute\Parameter\CallServiceMethod;
use Donquixote\DID\ContainerToValue\ContainerToValue_ObjectMethodCall;
use Donquixote\DID\ContainerToValue\ContainerToValue_ServiceId;
use Donquixote\DID\ContainerToValue\ContainerToValueInterface;
use Donquixote\DID\Util\AttributesUtil;

class ParamToCTV_Attribute_CallServiceMethod implements ParamToCTVInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?ContainerToValueInterface {
    $attribute = AttributesUtil::getSingle($parameter, CallServiceMethod::class);
    if ($attribute === NULL) {
      return NULL;
    }
    return new ContainerToValue_ObjectMethodCall(
      new ContainerToValue_ServiceId($attribute->serviceId),
      $attribute->method,
      $attribute->args,
    );
  }

}
