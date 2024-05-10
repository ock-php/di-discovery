<?php

declare(strict_types=1);

namespace Donquixote\DID\Attribute\Parameter;

use Donquixote\DID\Exception\MalformedDeclarationException;
use Donquixote\Helpers\Util\MessageUtil;
use Donquixote\DID\Util\ReflectionTypeUtil;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;
use Donquixote\DID\ValueDefinition\ValueDefinitionInterface;

/**
 * Marks a parameter to expect a service from the container.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class GetService implements GetServiceInterface {

  /**
   * Constructor.
   *
   * @param string|null $id
   *   Id of the service, or NULL to use interface name.
   * @param string|null $serviceIdSuffix
   *   Suffix to append to a service id.
   *   This allows to disambiguate services that implement the same interface.
   */
  public function __construct(
    private readonly ?string $id = NULL,
    private readonly ?string $serviceIdSuffix = NULL,
  ) {}

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return \Donquixote\DID\ValueDefinition\ValueDefinitionInterface
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   */
  public function getArgumentDefinition(\ReflectionParameter $parameter): ValueDefinitionInterface {
    $id = $this->id
      ?? ReflectionTypeUtil::getClassLikeType($parameter);
    if ($id === NULL) {
      throw new MalformedDeclarationException(sprintf(
        'No service id and no class-like type were provided on %s.',
        MessageUtil::formatReflector($parameter),
      ));
    }
    if ($this->serviceIdSuffix !== NULL) {
      $id .= '.' . $this->serviceIdSuffix;
    }
    return new ValueDefinition_GetService($id);
  }

  /**
   * {@inheritdoc}
   */
  public function paramGetServiceId(\ReflectionParameter $parameter): string {
    $id = $this->id
      ?? ReflectionTypeUtil::getClassLikeType($parameter);
    if ($id === NULL) {
      throw new MalformedDeclarationException(sprintf(
        'No service id and no class-like type were provided on %s.',
        MessageUtil::formatReflector($parameter),
      ));
    }
    if ($this->serviceIdSuffix !== NULL) {
      $id .= '.' . $this->serviceIdSuffix;
    }
    return $id;
  }

}
