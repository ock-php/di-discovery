<?php

declare(strict_types=1);

namespace Ock\DID\Attribute;

/**
 * Marks namespace directories and inspectors used for service discovery.
 *
 * It can be placed on:
 * - Classes that implement ReflectionClassesIAInterface.
 *   These specify the directories to scan for classes with service definitions.
 * - Classes that implement FactoryInspectorInterface.
 *   These are used to discover services within a class or method.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class ServicesDiscovery {

}
