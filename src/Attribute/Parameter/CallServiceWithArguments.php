<?php

declare(strict_types = 1);

namespace Ock\DID\Attribute\Parameter;

use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\ClassDiscovery\Util\ReflectionTypeUtil;
use Ock\Helpers\Util\MessageUtil;

/**
 * Treats the service as a callable.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class CallServiceWithArguments {

  /**
   * Constructor.
   *
   * @param string|null $serviceId
   *   Id of the service, or NULL to use 'get.' plus the interface name.
   * @param array $args
   *   Fixed argument values.
   * @param array<int, int> $forwardArgsMap
   *   Reference to arguments passed to the parameterized service.
   */
  public function __construct(
    private readonly ?string $serviceId = NULL,
    public readonly array $args = [],
    public readonly array $forwardArgsMap = [0],
  ) {}

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return string
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
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
