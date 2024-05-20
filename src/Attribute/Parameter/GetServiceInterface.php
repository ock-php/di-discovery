<?php

declare(strict_types=1);

namespace Ock\DID\Attribute\Parameter;

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
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
   */
  public function paramGetServiceId(\ReflectionParameter $parameter): string;

}
