<?php

declare(strict_types=1);

namespace Ock\DID\Attribute;

/**
 * The object can provide a service id for a container.
 */
interface ServiceIdHavingAttributeInterface {

  /**
   * Gets a service id for a DI container.
   *
   * @return string|null
   */
  public function getServiceId(): ?string;

}
