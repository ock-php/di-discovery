<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Donquixote\ClassDiscovery\Util\AttributesUtil;
use Donquixote\DID\Attribute\Parameter\GetContainer;
use Ock\Egg\Egg\Egg_Container;
use Ock\Egg\Egg\EggInterface;

class ParamToCTV_Attribute_GetContainer implements ParamToCTVInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?EggInterface {
    return AttributesUtil::hasSingle($parameter, GetContainer::class)
      ? new Egg_Container()
      : NULL;
  }

}
