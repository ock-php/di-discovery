<?php

declare(strict_types=1);

namespace Ock\DID\Attribute\Parameter;

use Ock\DID\ValueDefinition\ValueDefinition_GetContainer;
use Ock\DID\ValueDefinition\ValueDefinitionInterface;

/**
 * Marks a parameter to expect a service from the container.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class GetContainer implements ServiceArgumentAttributeInterface {

  public function getArgumentDefinition(\ReflectionParameter $parameter): ValueDefinitionInterface {
    return new ValueDefinition_GetContainer();
  }

}
