<?php

declare(strict_types = 1);

namespace Ock\DID\Discovery;

use Ock\ClassDiscovery\Shared\ReflectionClassesIAHavingBase;

class Discovery extends ReflectionClassesIAHavingBase {

  const ON_CLASS = -1;

  public function buildMap(): array {
    $map = [];
    $i = 0;
    /** @var \ReflectionClass $reflectionClass */
    foreach ($this->itReflectionClasses() as $reflectionClass) {
      $class = $reflectionClass->getName();
      foreach ($reflectionClass->getAttributes() as $attribute) {
        $map[$attribute->getName()][$class][self::ON_CLASS] = $i;
        ++$i;
      }
      foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        if ($reflectionMethod->getDeclaringClass()->getName() !== $class) {
          continue;
        }
        foreach ($reflectionMethod->getAttributes() as $attribute) {
          $map[$attribute->getName()][$class][$reflectionMethod->getName()] = $i;
          ++$i;
        }
      }
    }
    return $map;
  }

  public function discover(): \Iterator {
    $map = [];
    $i = 0;
    /** @var \ReflectionClass $reflectionClass */
    foreach ($this->itReflectionClasses() as $reflectionClass) {
      $class = $reflectionClass->getName();
      foreach ($this->readers as $type => $reader) {
        foreach ($reflectionClass->getAttributes($type, \ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
          yield from $reader->visitClassAttribute($reflectionClass, $attribute->newInstance());
          $map[$attribute->getName()][$class][self::ON_CLASS] = $i;
          ++$i;
        }
      }
      foreach ($reflectionClass->getAttributes() as $attribute) {
        $map[$attribute->getName()][$class][self::ON_CLASS] = $i;
        ++$i;
      }
      foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        if ($reflectionMethod->getDeclaringClass()->getName() !== $class) {
          continue;
        }
        foreach ($reflectionMethod->getAttributes() as $attribute) {
          $map[$attribute->getName()][$class][$reflectionMethod->getName()] = $i;
          ++$i;
        }
      }
    }
    return $map;
  }

  public function accept($visitor): void {
    $i = 0;
    /** @var \ReflectionClass $reflectionClass */
    foreach ($this->itReflectionClasses() as $reflectionClass) {
      $class = $reflectionClass->getName();
      foreach ($reflectionClass->getAttributes() as $attribute) {
        $visitor->visitClassAttribute($reflectionClass, $attribute->newInstance(), $i);
        ++$i;
      }
      foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        if ($reflectionMethod->getDeclaringClass()->getName() !== $class) {
          continue;
        }
        foreach ($reflectionMethod->getAttributes() as $attribute) {
          $visitor->visitMethodAttribute($reflectionClass, $attribute, $i);
          ++$i;
        }
      }
    }
  }

}
