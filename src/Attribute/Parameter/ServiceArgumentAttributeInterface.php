<?php

/**
 * @file
 */

declare(strict_types=1);

namespace Donquixote\DID\Attribute\Parameter;

interface ServiceArgumentAttributeInterface {

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return \Donquixote\DID\ValueDefinition\ValueDefinitionInterface|mixed
   *
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  public function getArgumentDefinition(\ReflectionParameter $parameter): mixed;

}
