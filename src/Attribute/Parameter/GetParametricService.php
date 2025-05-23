<?php

declare(strict_types = 1);

namespace Ock\DID\Attribute\Parameter;

use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\ClassDiscovery\Util\ReflectionTypeUtil;
use Ock\Helpers\Util\MessageUtil;
use Ock\ReflectorAwareAttributes\AttributeConstructor;

/**
 * Treats the service as a callable.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class GetParametricService {

  private readonly ?string $virtualServiceId;

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
    ?string $virtualServiceId = NULL,
    public readonly array $args = [],
  ) {
    $this->virtualServiceId = $virtualServiceId
      ?? self::guessVirtualServiceId();
  }

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return string
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
   */
  public function paramGetServiceId(\ReflectionParameter $parameter): string {
    if ($this->virtualServiceId !== NULL) {
      return $this->virtualServiceId;
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

  /**
   * @return string
   */
  public function getCallableServiceId(): string {
    if ($this->virtualServiceId === NULL) {
      throw new \RuntimeException('Attribute was not initialized. Please call setReflector().');
    }
    return 'get.' . $this->virtualServiceId;
  }

  /**
   * @return class-string
   *
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
   */
  private static function guessVirtualServiceId(): string {
    $reflector = AttributeConstructor::getReflector();
    if (!$reflector instanceof \ReflectionParameter) {
      throw new \InvalidArgumentException(sprintf(
        'Unexpected reflector type %s.',
        get_class($reflector),
      ));
    }
    $type = ReflectionTypeUtil::getClassLikeType($reflector);
    if ($type === NULL) {
      throw new MalformedDeclarationException(sprintf(
        'No service id and no class-like type were provided on %s.',
        MessageUtil::formatReflector($reflector),
      ));
    }
    return $type;
  }

}
