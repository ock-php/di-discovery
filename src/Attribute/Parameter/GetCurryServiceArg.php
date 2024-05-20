<?php

declare(strict_types=1);

namespace Ock\DID\Attribute\Parameter;

/**
 * Marks a parameter to expect a service from the container.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class GetCurryServiceArg {

  /**
   * Constructor.
   *
   * @param int|null $position
   *   Position of the argument, or NULL for automatic position.
   */
  public function __construct(
    public readonly ?int $position = NULL,
  ) {}

}
