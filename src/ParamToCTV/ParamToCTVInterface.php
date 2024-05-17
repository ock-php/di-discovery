<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Ock\Egg\Egg\EggInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(self::SERVICE_TAG)]
interface ParamToCTVInterface {

  const SERVICE_TAG = self::class;

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return \Ock\Egg\Egg\EggInterface|null
   *
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?EggInterface;

}
