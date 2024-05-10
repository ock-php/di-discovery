<?php

declare(strict_types=1);

namespace Donquixote\DID\Attribute\Parameter;

/**
 * Gets a callable service from the container.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
interface GetServiceInterface extends ServiceArgumentAttributeInterface {

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return string
   *
   * @throws \Donquixote\ClassDiscovery\Exception\MalformedDeclarationException
   */
  public function paramGetServiceId(\ReflectionParameter $parameter): string;

}
