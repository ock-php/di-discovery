<?php

declare(strict_types=1);

namespace Ock\DID\Attribute\Parameter;

use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\ClassDiscovery\Util\ReflectionTypeUtil;
use Ock\DID\ValueDefinition\ValueDefinition_GetService;
use Ock\DID\ValueDefinition\ValueDefinitionInterface;
use Ock\Helpers\Util\MessageUtil;

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
   * @return \Ock\DID\ValueDefinition\ValueDefinitionInterface
   *
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
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
