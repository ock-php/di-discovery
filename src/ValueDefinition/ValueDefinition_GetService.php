<?php

declare(strict_types = 1);

namespace Donquixote\DID\ValueDefinition;

final class ValueDefinition_GetService implements ValueDefinitionInterface {

  /**
   * Constructor.
   *
   * @param string $id
   */
  public function __construct(
    public readonly string $id,
  ) {}

}
