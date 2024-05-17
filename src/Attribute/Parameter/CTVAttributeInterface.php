<?php

declare(strict_types=1);

namespace Donquixote\DID\Attribute\Parameter;

use Ock\Egg\Egg\EggInterface;

interface CTVAttributeInterface extends ServiceArgumentAttributeInterface {

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return \Ock\Egg\Egg\EggInterface
   */
  public function paramGetCTV(\ReflectionParameter $parameter): EggInterface;

}
