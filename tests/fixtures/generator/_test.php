<?php

/**
 * @var string $php
 *   Original php snippet.
 */

use Ock\DID\Tests\Util\TestUtil;
use Ock\DID\ValueDefinition\ValueDefinition_GetContainer;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;

$definition = eval($php);
$evaluator = TestUtil::createDummyEvaluator();
$container = $evaluator->evaluate(new ValueDefinition_GetContainer());
$generator = new \Ock\DID\ValueDefinitionToPhp\ValueDefinitionToPhp();

$definitionValue = $evaluator->evaluate($definition);
$definitionExpression = $generator->generate($definition);

$expressionValue = (eval("return static function (\$container) {
  return $definitionExpression; 
};"))($container);

try {
  Assert::assertSame(
    serialize($definitionValue),
    serialize($expressionValue),
  );
}
catch (AssertionFailedError $e) {
  throw $e;
}
catch (\Exception $e) {
  // Failed to serialize. Try something else.
  Assert::assertSame(
    get_debug_type($definitionValue),
    get_debug_type($expressionValue),
  );
  if ($definitionValue instanceof \Closure && $expressionValue instanceof \Closure) {
    $args = ['a', 'b', 'c', 'd', 'e'];
    Assert::assertSame(
      serialize($definitionValue(...$args)),
      serialize($expressionValue(...$args)),
    );
  }
}
