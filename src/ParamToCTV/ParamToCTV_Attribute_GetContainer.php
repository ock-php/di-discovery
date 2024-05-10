<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Donquixote\DID\Attribute\Parameter\GetContainer;
use Donquixote\DID\ContainerToValue\ContainerToValue_Container;
use Donquixote\DID\ContainerToValue\ContainerToValueInterface;
use Donquixote\DID\Util\AttributesUtil;

class ParamToCTV_Attribute_GetContainer implements ParamToCTVInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?ContainerToValueInterface {
    return AttributesUtil::hasSingle($parameter, GetContainer::class)
      ? new ContainerToValue_Container()
      : NULL;
  }

}
