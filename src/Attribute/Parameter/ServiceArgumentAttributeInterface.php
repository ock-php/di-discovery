<?php

/**
 * @file
 */

declare(strict_types=1);

namespace Ock\DID\Attribute\Parameter;

interface ServiceArgumentAttributeInterface {

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return \Ock\DID\ValueDefinition\ValueDefinitionInterface|mixed
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  public function getArgumentDefinition(\ReflectionParameter $parameter): mixed;

}
