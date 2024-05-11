<?php

declare(strict_types = 1);

namespace Donquixote\DID\ValueDefinitionToPhp;

use Donquixote\CodegenTools\Exception\CodegenException;
use Donquixote\Helpers\Util\MessageUtil;
use Donquixote\CodegenTools\Util\CodeGen;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Donquixote\DID\ValueDefinition\ValueDefinition_ClassName;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetArgument;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetContainer;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;
use Donquixote\DID\ValueDefinition\ValueDefinition_Parametric;
use Donquixote\DID\ValueDefinition\ValueDefinitionInterface;

class ValueDefinitionToPhp implements ValueDefinitionToPhpInterface {

  /**
   * Constructor.
   *
   * @param string $containerPlaceholder
   */
  public function __construct(
    private string $containerPlaceholder = '$container',
  ) {}

  /**
   * @param string $containerPlaceholder
   *
   * @return static
   */
  public function withContainerPlaceholder(string $containerPlaceholder): static {
    $clone = clone $this;
    $clone->containerPlaceholder = $containerPlaceholder;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function generate(mixed $definition, bool $enclose = FALSE): string {
    return match (gettype($definition)) {
      'array' => CodeGen::phpArray(
        $this->generateMultiple($definition),
      ),
      'boolean',
      'integer',
      'string',
      'double',
      'NULL' => var_export($definition, TRUE),
      'resource',
      'resource (closed)' => throw new CodegenException('Cannot export resources.'),
      'object' => match (get_class($definition)) {
        ValueDefinition_GetService::class => $this->containerPlaceholder
          . '->get(' . var_export($definition->id, TRUE) . ')',
        ValueDefinition_Call::class => CodeGen::phpCallFqn(
          $this->generateCallFqn($definition->callback),
          $this->generateMultiple($definition->args),
        ),
        ValueDefinition_Parametric::class => CodeGen::phpEncloseIf(
          'static fn (...$args) => ' . $this->generate($definition->value),
          $enclose,
        ),
        ValueDefinition_Construct::class => CodeGen::phpCallFqn(
          is_string($definition->class)
            ? 'new \\' . $definition->class
            : 'new (' . $this->generate($definition->class) . ')',
          $this->generateMultiple($definition->args),
          $enclose,
        ),
        ValueDefinition_GetArgument::class => '$args[' . $definition->position . ']',
        ValueDefinition_ClassName::class => '\\' . $definition->class . '::class',
        ValueDefinition_GetContainer::class => $this->containerPlaceholder,
        ValueDefinition_CallObjectMethod::class => CodeGen::phpCallMethod(
          $this->generate($definition->object, true),
          is_string($definition->method)
            ? $definition->method
            : '{' . $this->generate($definition->method) . '}',
          $this->generateMultiple($definition->args),
        ),
        default => $definition instanceof ValueDefinitionInterface
          ? throw new CodegenException(sprintf(
            'Unknown value definition type %s.',
            get_class($definition),
          ))
          : throw new CodegenException(sprintf(
            'Objects must implement %s. Found %s.',
            ValueDefinitionInterface::class,
            MessageUtil::formatValue($definition),
          )),
      },
      default => throw new CodegenException(sprintf(
        'Unexpected value %s.',
        MessageUtil::formatValue($definition),
      )),
    };
  }

  /**
   * @param mixed $definition
   *   Definition for a callable value.
   *
   * @return string
   * @throws \Donquixote\CodegenTools\Exception\CodegenException
   */
  private function generateCallFqn(mixed $definition): string {
    if (is_string($definition)) {
      // @todo Validate string content.
      return '\\' . $definition;
    }
    if (!is_array($definition)) {
      return $this->generate($definition, true);
    }
    [$classOrObjectDefinition, $methodDefinition] = $definition;
    if (is_string($classOrObjectDefinition)) {
      $methodPhp = is_string($methodDefinition)
        ? $methodDefinition
        : '{' . $this->generate($methodDefinition) . '}';
      return "\\$classOrObjectDefinition::$methodPhp";
    }
    $classOrObjectPhp = $this->generate($classOrObjectDefinition, true);
    if (str_starts_with($classOrObjectPhp, '(new ')) {
      $methodPhp = is_string($methodDefinition)
        ? $methodDefinition
        : '{' . $this->generate($methodDefinition) . '}';
      return "$classOrObjectPhp->$methodPhp";
    }
    // Use generic callable syntax, because it could be static or not.
    return CodeGen::phpArray([
      $this->generate($classOrObjectDefinition),
      $this->generate($methodDefinition),
    ]);
  }

  /**
   * @param array $definitions
   *
   * @return array
   */
  private function generateMultiple(array $definitions): array {
    return array_map([$this, 'generate'], $definitions);
  }

}
