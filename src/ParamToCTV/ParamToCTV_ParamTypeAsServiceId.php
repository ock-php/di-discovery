<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Donquixote\DID\ContainerToValue\ContainerToValue_ServiceId;
use Donquixote\DID\ContainerToValue\ContainerToValueInterface;
use Donquixote\ClassDiscovery\Util\ReflectionTypeUtil;

class ParamToCTV_ParamTypeAsServiceId implements ParamToCTVInterface {

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface|null
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?ContainerToValueInterface {
    $class = ReflectionTypeUtil::getClassLikeType($parameter);
    if ($class === NULL) {
      return NULL;
    }
    return new ContainerToValue_ServiceId($class);
  }

}
