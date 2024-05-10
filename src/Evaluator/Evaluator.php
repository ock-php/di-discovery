<?php

declare(strict_types = 1);

namespace Donquixote\DID\Evaluator;

use Donquixote\DID\Container\Container_Empty;
use Donquixote\DID\Exception\ContainerToValueException;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Donquixote\DID\ValueDefinition\ValueDefinition_ClassName;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetArgument;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetContainer;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;
use Donquixote\DID\ValueDefinition\ValueDefinition_Parametric;
use Donquixote\DID\ValueDefinition\ValueDefinitionInterface;
use Psr\Container\ContainerInterface;

class Evaluator implements EvaluatorInterface {

  private ContainerInterface $container;

  private ?array $parametricArgs = NULL;

  /**
   * Constructor.
   *
   * @param \Psr\Container\ContainerInterface|null $container
   *   The container, or NULL for empty container.
   */
  public function __construct(?ContainerInterface $container) {
    $this->container = $container ?? new Container_Empty();
  }

  /**
   * {@inheritdoc}
   */
  public function withContainer(ContainerInterface $container): static {
    $clone = clone $this;
    $clone->container = $container;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function evaluate(mixed $definition): mixed {
    if (is_array($definition)) {
      $result = [];
      foreach ($definition as $k => $v) {
        $result[$k] = $this->evaluate($v);
      }
      return $result;
    }
    if (!$definition instanceof ValueDefinitionInterface) {
      return $definition;
    }
    if ($definition instanceof ValueDefinition_GetService) {
      return $this->container->get($definition->id);
    }
    if ($definition instanceof ValueDefinition_GetContainer) {
      return $this->container;
    }
    if ($definition instanceof ValueDefinition_Construct) {
      $class = $this->evaluate($definition->class);
      $args = $this->evaluate($definition->args);
      return new $class(...$args);
    }
    if ($definition instanceof ValueDefinition_Call) {
      $callable = $this->evaluate($definition->callback);
      $args = $this->evaluate($definition->args);
      return $callable(...$args);
    }
    if ($definition instanceof ValueDefinition_CallObjectMethod) {
      $object = $this->evaluate($definition->object);
      $method = $this->evaluate($definition->method);
      $args = $this->evaluate($definition->args);
      return $object->$method(...$args);
    }
    if ($definition instanceof ValueDefinition_Parametric) {
      return fn (...$args) => $this->withParametricArgs($args)->evaluate($definition->value);
    }
    if ($definition instanceof ValueDefinition_GetArgument) {
      /** @noinspection PhpVoidFunctionResultUsedInspection */
      return $this->parametricArgs[$definition->position]
        ?? (array_key_exists($definition->position,  $this->parametricArgs)
          ? NULL
          : $this->fail($this->parametricArgs !== NULL
            ? "Missing parametric argument $definition->position."
            : "Parametric argument outside of parametric service."
          )
        );
    }
    if ($definition instanceof ValueDefinition_ClassName) {
      return $definition->class;
    }
    throw new \RuntimeException(
      sprintf(
        'Unknown value definition type %s.',
        get_class($definition),
      )
    );
  }

  /**
   * @param array $args
   *
   * @return \Donquixote\DID\Evaluator\Evaluator
   */
  private function withParametricArgs(array $args): Evaluator {
    $clone = clone $this;
    $clone->parametricArgs = $args;
    return $clone;
  }

  /**
   * @param string $message
   *
   * @return never
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   */
  private function fail(string $message): never {
    throw new ContainerToValueException($message);
  }

}
