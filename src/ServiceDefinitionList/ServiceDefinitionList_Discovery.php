<?php

declare(strict_types = 1);

namespace Donquixote\DID\ServiceDefinitionList;

use Donquixote\ClassDiscovery\Shared\ReflectionClassesIAHavingBase;
use Donquixote\DID\Attribute\ServiceDefinitionAttributeInterface;
use Donquixote\ClassDiscovery\Util\AttributesUtil;

/**
 * @template-extends \Donquixote\DID\ServiceDefinitionList\ServiceDefinitionListInterface<false>
 */
class ServiceDefinitionList_Discovery extends ReflectionClassesIAHavingBase implements ServiceDefinitionListInterface {

  /**
   * {@inheritdoc}
   */
  public function getDefinitions(): array {
    $definitions = [];
    /** @var \ReflectionClass $reflectionClass */
    foreach ($this->itReflectionClasses() as $reflectionClass) {
      /** @var \Donquixote\DID\Attribute\ServiceDefinitionAttributeInterface[] $serviceAttributes */
      $serviceAttributes = AttributesUtil::getAll($reflectionClass, ServiceDefinitionAttributeInterface::class);
      foreach ($serviceAttributes as $serviceAttribute) {
        $definitions[] = $serviceAttribute->onClass($reflectionClass);
      }
      foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        /** @var \Donquixote\DID\Attribute\ServiceDefinitionAttributeInterface[] $serviceAttributes */
        $serviceAttributes = AttributesUtil::getAll($reflectionMethod, ServiceDefinitionAttributeInterface::class);
        foreach ($serviceAttributes as $serviceAttribute) {
          $definitions[] = $serviceAttribute->onMethod($reflectionMethod);
        }
      }
    }
    return $definitions;
  }

}
