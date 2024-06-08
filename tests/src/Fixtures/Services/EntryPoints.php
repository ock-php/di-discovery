<?php

declare(strict_types = 1);

namespace Ock\DID\Tests\Fixtures\Services;

use Ock\ClassDiscovery\FactsIA\FactsIA_InspectFactories;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA;
use Ock\DID\Inspector\FactoryInspector_ServiceDefinition;
use Ock\DID\ServiceDefinitionList\ServiceDefinitionList_Discovery;
use Ock\DID\ServiceDefinitionList\ServiceDefinitionListInterface;

class EntryPoints {

  public static function getServiceDefinitionList(): ServiceDefinitionListInterface {
    $classes = ReflectionClassesIA::psr4FromKnownClass(self::class);
    $inspector = new FactoryInspector_ServiceDefinition();
    $discovery = new FactsIA_InspectFactories($classes, $inspector);
    return new ServiceDefinitionList_Discovery($discovery);
  }

}
