<?php

declare(strict_types = 1);

namespace Donquixote\DID\Attribute\Parameter;

#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class CallServiceMethodWithArguments {

  /**
   * Constructor.
   *
   * @param string $serviceId
   * @param string $method
   * @param array $args
   * @param array $forwardArgsMap
   */
  public function __construct(
    public readonly string $serviceId,
    public readonly string $method,
    public readonly array $args = [],
    public readonly array $forwardArgsMap = [0],
  ) {}

}
