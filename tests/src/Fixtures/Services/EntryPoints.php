<?php

declare(strict_types = 1);

namespace Donquixote\DID\Tests\Fixtures\Services;

use Donquixote\ClassDiscovery\Discovery\FactoryDiscovery;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA;
use Donquixote\DID\Inspector\FactoryInspector_ServiceDefinition;
use Donquixote\DID\ServiceDefinitionList\ServiceDefinitionList_Discovery;
use Donquixote\DID\ServiceDefinitionList\ServiceDefinitionListInterface;

class EntryPoints {

  public static function getServiceDefinitionList(): ServiceDefinitionListInterface {
    $classes = ReflectionClassesIA::psr4FromKnownClass(self::class);
    $inspector = new FactoryInspector_ServiceDefinition();
    $discovery = new FactoryDiscovery($classes, $inspector);
    return new ServiceDefinitionList_Discovery($discovery);
  }

}
