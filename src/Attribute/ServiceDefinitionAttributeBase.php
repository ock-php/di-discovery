<?php
declare(strict_types=1);

namespace Ock\DID\Attribute;

/**
 * Marks a class or method as a service.
 */
#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_CLASS|\Attribute::IS_REPEATABLE)]
abstract class ServiceDefinitionAttributeBase {

  /**
   * Constructor.
   *
   * @param string|null $serviceId
   *   The id, or NULL to use the interface name or the class name.
   * @param string|null $serviceIdSuffix
   *   Suffix to append to a service id.
   *   This allows to distinguish services that implement the same interface.
   */
  public function __construct(
    public readonly ?string $serviceId = NULL,
    public readonly ?string $serviceIdSuffix = NULL,
  ) {}

  /**
   * @return bool
   */
  abstract public function isParametric(): bool;

}
