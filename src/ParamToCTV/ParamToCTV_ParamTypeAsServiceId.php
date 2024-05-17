<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Ock\Egg\Egg\Egg_ServiceId;
use Ock\Egg\Egg\EggInterface;
use Donquixote\ClassDiscovery\Util\ReflectionTypeUtil;

class ParamToCTV_ParamTypeAsServiceId implements ParamToCTVInterface {

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return \Ock\Egg\Egg\EggInterface|null
   * @throws \Donquixote\ClassDiscovery\Exception\MalformedDeclarationException
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?EggInterface {
    $class = ReflectionTypeUtil::getClassLikeType($parameter);
    if ($class === NULL) {
      return NULL;
    }
    return new Egg_ServiceId($class);
  }

}
