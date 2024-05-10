<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Donquixote\DID\ContainerToValue\ContainerToValueInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(self::SERVICE_TAG)]
interface ParamToCTVInterface {

  const SERVICE_TAG = self::class;

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface|null
   *
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?ContainerToValueInterface;

}
