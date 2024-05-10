<?php

declare(strict_types = 1);

namespace Donquixote\DID\Attribute\Parameter;

use Donquixote\DID\Exception\MalformedDeclarationException;
use Donquixote\Helpers\Util\MessageUtil;
use Donquixote\DID\Util\ReflectionTypeUtil;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;

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
