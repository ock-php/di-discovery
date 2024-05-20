<?php

declare(strict_types = 1);

namespace Ock\DID\Evaluator;

use Psr\Container\ContainerInterface;

interface EvaluatorInterface {

  /**
   * Gets a new evaluator with the container provided.
   *
   * @param \Psr\Container\ContainerInterface $container
   *
   * @return self
   */
  public function withContainer(ContainerInterface $container): self;

  /**
   * @param mixed|\Ock\DID\ValueDefinition\ValueDefinitionInterface $definition
   *
   * @return mixed
   *
   * @throws \Psr\Container\ContainerExceptionInterface
   */
  public function evaluate(mixed $definition): mixed;

}
