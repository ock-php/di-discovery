<?php

declare(strict_types=1);

namespace Donquixote\DID\Inspector;

use Donquixote\ClassDiscovery\Inspector\FactoryInspectorInterface;
use Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Donquixote\ClassDiscovery\Util\AttributesUtil;
use Donquixote\DID\Attribute\ServiceDefinitionAttributeInterface;

/**
 * @template-implements FactoryInspectorInterface<\Donquixote\DID\ServiceDefinition\ServiceDefinition>
 */
class FactoryInspector_ServiceDefinition implements FactoryInspectorInterface {

  /**
   * {@inheritdoc}
   */
  public function findInFactory(FactoryReflectionInterface $reflector): \Iterator {
    $attributes = AttributesUtil::getAll($reflector, ServiceDefinitionAttributeInterface::class);
    foreach ($attributes as $attribute) {
      if ($reflector instanceof \ReflectionClass) {
        yield $attribute->onClass($reflector);
      }
      elseif ($reflector instanceof \ReflectionMethod) {
        yield $attribute->onMethod($reflector);
      }
    }
  }

}
