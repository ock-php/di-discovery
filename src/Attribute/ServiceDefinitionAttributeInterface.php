<?php

/**
 * @file
 */

declare(strict_types = 1);

namespace Ock\DID\Attribute;

use Ock\DID\ServiceDefinition\ServiceDefinition;

/**
 * Marks a class or method as a parameterized service.
 *
 * The actual service object in the DI container will be just a callback, but
 * the return value of that callback will be an instance of the class, or a
 * return value of the method that was marked.
 *
 * @see \Ock\DID\EggList\EggList_Discovery_ParameterizedServiceAttribute
 */
interface ServiceDefinitionAttributeInterface {

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Ock\DID\ServiceDefinition\ServiceDefinition
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  public function onClass(\ReflectionClass $reflectionClass): ServiceDefinition;

  /**
   * @param \ReflectionMethod $reflectionMethod
   *
   * @return \Ock\DID\ServiceDefinition\ServiceDefinition
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  public function onMethod(\ReflectionMethod $reflectionMethod): ServiceDefinition;

}
