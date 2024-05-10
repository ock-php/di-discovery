<?php

declare(strict_types = 1);

namespace Donquixote\DID\Attribute;

use Donquixote\DID\Exception\DiscoveryException;
use Donquixote\DID\Exception\MalformedDeclarationException;
use Donquixote\CodegenTools\Util\MessageUtil;
use Donquixote\DID\Util\ReflectionTypeUtil;

class ServiceDiscoveryUtil {

  /**
   * @param \Reflector $reflector
   *
   * @return string
   *
   * @throws \Donquixote\DID\Exception\DiscoveryException
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
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
   * @throws \Donquixote\DID\Exception\DiscoveryException
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
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
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  public static function classGetTypeName(\ReflectionClass $reflectionClass): string {
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
