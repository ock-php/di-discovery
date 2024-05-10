<?php

declare(strict_types = 1);

namespace Donquixote\DID\Evaluator;

use Donquixote\CodegenTools\Exception\EvaluationException;
use Psr\Container\ContainerInterface;

class Evaluator_Empty implements EvaluatorInterface {

  /**
   * {@inheritdoc}
   */
  public function withContainer(ContainerInterface $container): static {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function evaluate(mixed $definition): mixed {
    throw new EvaluationException(sprintf(
      'Unknown value definition type %s.',
      get_class($definition),
    ));
  }

}
