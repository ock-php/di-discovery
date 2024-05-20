<?php

declare(strict_types = 1);

namespace Ock\DID\Attribute;

use Ock\ClassDiscovery\Exception\DiscoveryException;
use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\ClassDiscovery\Util\ReflectionTypeUtil;
use Ock\Helpers\Util\MessageUtil;

class ServiceDiscoveryUtil {

  /**
   * @param \Reflector $reflector
   *
   * @return string
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
   */
  public static function reflectorGetTypeName(\Reflector $reflector): string {
    if ($reflector instanceof \ReflectionClass) {
      if (!$reflector->isInstantiable()) {
        throw new MalformedDeclarationException(sprintf(
          'Class %s is annotated as a service, but it is not instantiable.', MessageUtil::formatReflector($reflector),
        ));
      }
      return self::classGetTypeName($reflector);
    }
    if ($reflector instanceof \ReflectionFunctionAbstract) {
      return self::functionGetTypeName($reflector);
    }
    throw new \InvalidArgumentException(sprintf(
      'Unexpected reflector type %s.',
      get_class($reflector),
    ));
  }

  /**
   * @param \ReflectionFunctionAbstract $reflectionFunction
   *
   * @return string
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
   */
  public static function functionGetTypeName(\ReflectionFunctionAbstract $reflectionFunction): string {
    $returnClass = ReflectionTypeUtil::requireGetClassLikeType($reflectionFunction);
    try {
      $returnReflectionClass = new \ReflectionClass($returnClass);
    }
    catch (\ReflectionException $e) {
      throw new DiscoveryException($e->getMessage(), 0, $e);
    }
    if ($returnReflectionClass->isInterface()) {
      return $returnClass;
    }
    return self::classGetTypeName($returnReflectionClass);
  }

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return string
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  public static function classGetTypeName(\ReflectionClass $reflectionClass): string {
    if ($reflectionClass->isInterface()) {
      return $reflectionClass->getName();
    }
    $interfaces = $reflectionClass->getInterfaceNames();
    if (!$interfaces) {
      return $reflectionClass->getName();
    }
    if (count($interfaces) === 1) {
      return reset($interfaces);
    }
    // Remove interfaces that could be indirect.
    $interfaces = array_combine($interfaces, $interfaces);
    foreach ($reflectionClass->getInterfaces() as $reflectionInterface) {
      foreach ($reflectionInterface->getInterfaceNames() as $parentInterface) {
        unset($interfaces[$parentInterface]);
      }
    }
    if (count($interfaces) === 1) {
      return reset($interfaces);
    }
    throw new DiscoveryException(sprintf(
      'Expected 1 non-indirect interface on class %s, found %d. Please specify the service id explicitly.',
      $reflectionClass->getName(),
      count($interfaces),
    ));
  }

}
