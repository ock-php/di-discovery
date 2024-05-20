<?php

declare(strict_types = 1);

namespace Ock\DID\Attribute\Parameter;

use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\ClassDiscovery\Util\ReflectionTypeUtil;
use Ock\DID\ValueDefinition\ValueDefinition_Call;
use Ock\DID\ValueDefinition\ValueDefinition_GetService;
use Ock\Helpers\Util\MessageUtil;

/**
 * Treats the service as a callable.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class CallParametricService implements ServiceArgumentAttributeInterface {

  /**
   * Constructor.
   *
   * @param string|null $virtualServiceId
   *   Id of the "virtual service", that is, the object being returned from the
   *   callable.
   * @param array $args
   *   Fixed argument values.
   */
  public function __construct(
    private readonly ?string $virtualServiceId = NULL,
    public readonly array $args = [],
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getArgumentDefinition(\ReflectionParameter $parameter): ValueDefinition_Call {
    $virtualServiceId = $this->virtualServiceId
      ?? ReflectionTypeUtil::getClassLikeType($parameter);
    if ($virtualServiceId === NULL) {
      throw new MalformedDeclarationException(sprintf(
        'No service id and no class-like type were provided on %s.',
        MessageUtil::formatReflector($parameter),
      ));
    }
    $id = 'get.' . $virtualServiceId;
    return new ValueDefinition_Call(
      new ValueDefinition_GetService($id),
      $this->args,
    );
  }

}
