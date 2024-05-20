<?php

declare(strict_types = 1);

namespace Ock\DID\ValueDefinitionProcessor;

use Ock\DID\FlatService;
use Ock\DID\ValueDefinition\ValueDefinition_Call;
use Ock\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Ock\DID\ValueDefinition\ValueDefinition_ClassName;
use Ock\DID\ValueDefinition\ValueDefinition_Construct;
use Ock\DID\ValueDefinition\ValueDefinition_GetArgument;
use Ock\DID\ValueDefinition\ValueDefinition_GetContainer;
use Ock\DID\ValueDefinition\ValueDefinition_GetService;
use Ock\DID\ValueDefinition\ValueDefinition_Parametric;
use Ock\DID\ValueDefinition\ValueDefinitionInterface;

/**
 * Standardizes the value definition as a service definition.
 */
class ValueDefinitionProcessor_FlatServiceDefinition implements ValueDefinitionProcessorInterface {

  /**
   * @param \Ock\DID\ValueDefinition\ValueDefinitionInterface $definition
   *
   * @return \Ock\DID\ValueDefinition\ValueDefinitionInterface
   */
  public function process(ValueDefinitionInterface $definition): ValueDefinitionInterface {
    if ($definition instanceof ValueDefinition_GetService) {
      return new ValueDefinition_Call(
        [FlatService::class, 'get'],
        [$definition],
      );
    }

    if ($definition instanceof ValueDefinition_GetContainer) {
      return new ValueDefinition_Call(
        [FlatService::class, 'get'],
        [$definition],
      );
    }

    if ($definition instanceof ValueDefinition_Construct) {
      if (is_string($definition->class)) {
        if (NULL === ($args = $this->processArguments($definition->args))) {
          return $definition;
        }
        return new ValueDefinition_Call([FlatService::class, 'construct'], [
          $definition->class,
          count($definition->args),
          ...$args,
        ]);
      }
      // Class name itself is dynamic.
      $args = [$definition->class, ...$definition->args];
      $args = $this->processArguments($args) ?? $args;
      return new ValueDefinition_Call([FlatService::class, 'constructDynamic'], [
        count($definition->args) + 1,
        ...$args,
      ]);
    }

    if ($definition instanceof ValueDefinition_Call) {
      if (is_string($definition->callback)
        || (is_array($definition->callback)
          && is_string($definition->callback[0])
          && is_string($definition->callback[1])
        )
      ) {
        if (NULL === ($processedArgs = $this->processArguments($definition->args))) {
          return $definition;
        }
        return new ValueDefinition_Call([FlatService::class, 'call'], [
          $definition->callback,
          count($definition->args),
          ...$processedArgs,
        ]);
      }
      // Class name itself is dynamic.
      $args = [$definition->callback, ...$definition->args];
      $processedArgs = $this->processArguments($args) ?? $args;
      return new ValueDefinition_Call([FlatService::class, 'callDynamic'], [
        count($definition->args) + 1,
        ...$processedArgs,
      ]);
    }

    if ($definition instanceof ValueDefinition_CallObjectMethod) {
      // Class name itself is dynamic.
      $args = [[$definition->object, $definition->method], ...$definition->args];
      $processedArgs = $this->processArguments($args) ?? $args;
      return new ValueDefinition_Call([FlatService::class, 'callDynamic'], [
        count($definition->args) + 1,
        ...$processedArgs,
      ]);
    }

    if ($definition instanceof ValueDefinition_Parametric) {
      // Class name itself is dynamic.
      $args = [$definition->value];
      $processedArgs = $this->processArguments($args) ?? $args;
      return new ValueDefinition_Call([FlatService::class, 'parametric'], $processedArgs);
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
   * @return array|null
   */
  private function processArguments(array $args): ?array {
    $orig = $args;
    $n = count($args);
    $append = static function (mixed $value) use (&$args, &$n): int {
      $args[] = $value;
      return $n++;
    };
    for ($i = 0; $i < $n; ++$i) {
      $args[$i] = $this->processArgument($args[$i], $append);
    }
    if ($orig === $args) {
      return NULL;
    }
    return $args;
  }

  /**
   * @param mixed $arg
   * @param callable(mixed): int $append
   *
   * @return mixed
   */
  private function processArgument(mixed $arg, callable $append): mixed {
    if (is_array($arg) && $arg) {
      // Look for nested dynamic parts inside the array.
      return [
        'op' => 'array',
        'array' => array_map($append, $arg),
      ];
    }

    if (!$arg instanceof ValueDefinitionInterface) {
      if (is_object($arg)) {
        throw new \InvalidArgumentException(sprintf(
          'Unexpected %s object in value definition.',
          get_class($arg),
        ));
      }
      // This argument can remain as-is.
      return $arg;
    }

    if ($arg instanceof ValueDefinition_GetService) {
      // This argument can remain as-is.
      return $arg;
    }

    if ($arg instanceof ValueDefinition_GetContainer) {
      // This argument can remain as-is.
      return $arg;
    }

    if ($arg instanceof ValueDefinition_Call) {
      // This is not allowed in a "flat" service definition.
      return [
        'op' => 'call',
        'callback' => (is_array($arg->callback) && is_string($arg->callback[0]))
          ? $append([new ValueDefinition_ClassName($arg->callback[0]), $arg->callback[1]])
          : $append($arg->callback),
        'args' => array_map($append, $arg->args),
      ];
    }

    if ($arg instanceof ValueDefinition_Construct) {
      return [
        'op' => 'construct',
        'class' => $append($arg->class),
        'args' => array_map($append, $arg->args),
      ];
    }

    if ($arg instanceof ValueDefinition_GetArgument) {
      return [
        'op' => 'arg',
        'position' => $arg->position,
      ];
    }

    if ($arg instanceof ValueDefinition_CallObjectMethod) {
      return [
        'op' => 'call',
        'callback' => $append([$arg->object, $arg->method]),
        'args' => array_map($append, $arg->args),
      ];
    }

    if ($arg instanceof ValueDefinition_ClassName) {
      return $arg->class;
    }

    throw new \InvalidArgumentException(sprintf(
      'Unexpected %s object in value definition.',
      get_class($arg),
    ));
  }

}
