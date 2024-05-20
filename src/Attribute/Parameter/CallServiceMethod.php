<?php

declare(strict_types = 1);

namespace Ock\DID\Attribute\Parameter;

use Ock\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Ock\DID\ValueDefinition\ValueDefinition_GetService;
use Ock\DID\ValueDefinition\ValueDefinitionInterface;

#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class CallServiceMethod {

  /**
   * Constructor.
   *
   * @param string $serviceId
   * @param string $method
   * @param array $args
   */
  public function __construct(
    public readonly string $serviceId,
    public readonly string $method,
    public readonly array $args,
  ) {}

  public function withReflector(\Reflector $reflector): ValueDefinitionInterface {
    if (!$reflector instanceof \ReflectionParameter) {
      throw new \RuntimeException('Unexpected reflector type.');
    }
    return new ValueDefinition_CallObjectMethod(
      new ValueDefinition_GetService($this->serviceId),
      $this->method,
      $this->args,
    );
  }

}
