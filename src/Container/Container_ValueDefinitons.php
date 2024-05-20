<?php

declare(strict_types = 1);

namespace Ock\DID\Container;

use Ock\DID\Evaluator\Evaluator_Empty;
use Ock\DID\Evaluator\EvaluatorInterface;
use Ock\DID\Exception\ServiceNotFoundException;
use Ock\DID\ValueDefinition\ValueDefinition_Call;
use Ock\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Ock\DID\ValueDefinition\ValueDefinition_Construct;
use Ock\DID\ValueDefinition\ValueDefinition_GetContainer;
use Ock\DID\ValueDefinition\ValueDefinition_GetService;
use Ock\DID\ValueDefinition\ValueDefinitionInterface;
use Psr\Container\ContainerInterface;

class Container_ValueDefinitons implements ContainerInterface {

  /**
   * @var array
   */
  private array $cache = [];

  /**
   * @var \Ock\DID\Evaluator\EvaluatorInterface
   */
  private EvaluatorInterface $evaluator;

  /**
   * Constructor.
   *
   * @param array<string, \Ock\DID\ValueDefinition\ValueDefinitionInterface> $definitions
   */
  public function __construct(
    private array $definitions,
    ?EvaluatorInterface $evaluator,
  ) {
    $this->evaluator = $evaluator?->withContainer($this)
      ?? new Evaluator_Empty();
  }

  /**
   * @param \Ock\DID\Evaluator\EvaluatorInterface $evaluator
   *
   * @return static
   */
  public function withEvaluator(EvaluatorInterface $evaluator): static {
    $clone = clone $this;
    $clone->evaluator = $evaluator->withContainer($clone);
    $clone->cache = [];
    return $clone;
  }

  /**
   * @param \Ock\DID\Evaluator\EvaluatorInterface $evaluator
   *
   * @return static
   */
  public function withValueDefinitions(array $definitions): static {
    $clone = clone $this;
    $clone->evaluator = $this->evaluator->withContainer($clone);
    $clone->definitions = $definitions;
    $clone->cache = [];
    return $clone;
  }

  /**
   * @template T
   *
   * @param class-string<T>|string $id
   *
   * @return T|mixed
   *
   * @throws \Psr\Container\ContainerExceptionInterface
   */
  public function get(string $id): mixed {
    return $this->cache[$id]
      ??= $this->evaluator->evaluate($this->definitions[$id] ?? $this->fail($id));
  }

  /**
   * @param string $id
   *
   * @return never
   * @throws \Ock\DID\Exception\ServiceNotFoundException
   */
  private function fail(string $id): never {
    throw new ServiceNotFoundException(sprintf(
      'Cannot retrieve service %s.',
      $id,
    ));
  }

  /**
   * @param mixed $definition
   *
   * @return mixed
   *
   * @throws \Ock\DID\Exception\ContainerToValueException
   *
   * @todo Make this pluggable.
   */
  private function build(mixed $definition): mixed {
    if (is_array($definition)) {
      $result = [];
      foreach ($definition as $k => $v) {
        $result[$k] = $this->build($v);
      }
      return $result;
    }

    if (!$definition instanceof ValueDefinitionInterface) {
      return $definition;
    }

    if ($definition instanceof ValueDefinition_GetService) {
      return $this->get($definition->id);
    }

    if ($definition instanceof ValueDefinition_GetContainer) {
      return $this;
    }

    if ($definition instanceof ValueDefinition_Construct) {
      $class = $this->build($definition->class);
      $args = $this->build($definition->args);
      return new $class(...$args);
    }

    if ($definition instanceof ValueDefinition_Call) {
      $callable = $this->build($definition->callback);
      $args = $this->build($definition->args);
      return $callable(...$args);
    }

    if ($definition instanceof ValueDefinition_CallObjectMethod) {
      $object = $this->build($definition->object);
      $method = $this->build($definition->method);
      $args = $this->build($definition->args);
      return $object->$method(...$args);
    }

    throw new \RuntimeException(
      sprintf(
        'Unknown value definition type %s.',
        get_class($definition),
      )
    );
  }

  /**
   * {@inheritdoc}
   */
  public function has(string $id): bool {
    return isset($this->definitions[$id]);
  }

}
