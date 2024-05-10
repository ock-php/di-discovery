<?php

declare(strict_types = 1);

namespace Donquixote\DID\Attribute;

interface ReflectorAwareAttributeInterface {

  /**
   * Sets the reflector where the attribute was found.
   *
   * @param \Reflector $reflector
   *   The place where the attribute was found.
   *
   * @throws \Donquixote\ClassDiscovery\Exception\MalformedDeclarationException
   */
  public function setReflector(\Reflector $reflector): void;

}
