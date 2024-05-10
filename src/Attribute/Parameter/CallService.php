<?php

declare(strict_types = 1);

namespace Donquixote\DID\Attribute\Parameter;

use Donquixote\DID\Exception\MalformedDeclarationException;
use Donquixote\Helpers\Util\MessageUtil;
use Donquixote\ClassDiscovery\Util\ReflectionTypeUtil;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;
use Donquixote\DID\ValueDefinition\ValueDefinitionInterface;

/**
 * Treats the service as a callable.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class CallService {

  /**
   * Constructor.
   *
   * @param string $serviceId
   *   Id of the service which is callable.
   * @param array $args
   *   Fixed argument values.
   */
  public function __construct(
    private readonly string $serviceId,
    public readonly array $args = [],
  ) {}

  public function withReflector(\Reflector $reflector): ValueDefinitionInterface {
    if (!$reflector instanceof \ReflectionParameter) {
      throw new \RuntimeException('Unexpected reflector type.');
    }
    return new ValueDefinition_Call(
      new ValueDefinition_GetService($this->serviceId),
      $this->args,
    );
  }

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return string
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
   */
  public function paramGetServiceId(\ReflectionParameter $parameter): string {
    if ($this->serviceId !== NULL) {
      return $this->serviceId;
    }
    $type = ReflectionTypeUtil::getClassLikeType($parameter);
    if ($type === NULL) {
      throw new MalformedDeclarationException(sprintf(
        'No service id and no class-like type were provided on %s.',
        MessageUtil::formatReflector($parameter),
      ));
    }
    return 'get.' . $type;
  }

}
