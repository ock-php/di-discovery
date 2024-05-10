<?php

declare(strict_types=1);

namespace Donquixote\DID\Attribute\Parameter;

use Donquixote\DID\ContainerToValue\ContainerToValueInterface;

interface CTVAttributeInterface extends ServiceArgumentAttributeInterface {

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ContainerToValueInterface;

}
