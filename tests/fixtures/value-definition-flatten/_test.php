<?php

/**
 * @var string $php
 *   Original php snippet.
 */

use Ock\DID\Tests\Util\TestUtil;
use Ock\DID\ValueDefinitionProcessor\ValueDefinitionProcessor_FlatServiceDefinition;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;

$definition = eval($php);
$evaluator = TestUtil::createDummyEvaluator();
$definitionValue = $evaluator->evaluate($definition);
$processor = new ValueDefinitionProcessor_FlatServiceDefinition();
$processedDefinition = $processor->process($definition);
$processedDefinitionValue = $evaluator->evaluate($processedDefinition);

try {
  Assert::assertSame(
    serialize($definitionValue),
    serialize($processedDefinitionValue),
  );
}
catch (AssertionFailedError $e) {
  throw $e;
}
catch (\Exception) {
  // Failed to serialize. Try something else.
  Assert::assertSame(
    get_debug_type($definitionValue),
    get_debug_type($processedDefinitionValue),
  );
  if ($definitionValue instanceof \Closure && $processedDefinitionValue instanceof \Closure) {
    $args = ['a', 'b', 'c', 'd', 'e'];
    Assert::assertSame(
      serialize($definitionValue(...$args)),
      serialize($processedDefinitionValue(...$args)),
    );
  }
}
