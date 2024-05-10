<?php
declare(strict_types=1);

namespace Donquixote\DID\Attribute;

/**
 * Marks a class or method as a service.
 */
#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_CLASS|\Attribute::IS_REPEATABLE)]
final class Service extends ServiceDefinitionAttributeBase {

  protected function isParametric(): bool {
    return false;
  }

}
