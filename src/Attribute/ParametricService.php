<?php
declare(strict_types=1);

namespace Donquixote\DID\Attribute;

/**
 * Marks a class or method as a parameterized service.
 *
 * The actual service object in the DI container will be just a callback, but
 * the return value of that callback will be an instance of the class, or a
 * return value of the method that was marked.
 *
 * @see \Donquixote\DID\CTVList\CTVList_Discovery_ParameterizedServiceAttribute
 */
#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_CLASS|\Attribute::IS_REPEATABLE)]
final class ParametricService extends ServiceDefinitionAttributeBase {

  /**
   * Constructor.
   *
   * @param string|null $virtualServiceId
   *   Virtual service id identifying the return value of the callable service.
   * @param string|null $serviceIdSuffix
   *   Suffix to append to a service id.
   *   This allows to distinguish services that implement the same interface.
   */
  public function __construct(
    ?string $virtualServiceId = NULL,
    ?string $serviceIdSuffix = NULL,
  ) {
    parent::__construct($virtualServiceId, $serviceIdSuffix);
  }

  /**
   * {@inheritdoc}
   */
  protected function isParametric(): bool {
    return false;
  }

}
