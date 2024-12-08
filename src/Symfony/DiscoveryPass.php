<?php

declare(strict_types=1);

namespace Ock\DID\Symfony;

use Ock\ClassDiscovery\FactsIA\FactsIA_InspectFactories;
use Ock\ClassDiscovery\FactsIA\FactsIAInterface;
use Ock\ClassDiscovery\Inspector\FactoryInspector_Concat;
use Ock\ClassDiscovery\Inspector\FactoryInspectorInterface;
use Ock\ClassDiscovery\Reflection\ClassReflection;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA_Concat;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use Ock\DID\Attribute\ServicesDiscovery;
use Ock\Helpers\Util\MessageUtil;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class DiscoveryPass implements CompilerPassInterface {

  public function process(ContainerBuilder $container): void {
    $discovery = $this->buildFactsIA($container);
    foreach ($discovery as $id => $fact) {
      if ($fact instanceof Definition) {
        if (!is_string($id)) {
          throw new \RuntimeException(sprintf(
            "Expected a string key, got %s.",
            // Array keys have to be string or array.
            // Iterator keys can be anything.
            MessageUtil::formatValue($id),
          ));
        }
        $container->setDefinition($id, $fact);
      }
      elseif ($fact instanceof \Closure) {
        $rf = new \ReflectionFunction($fact);
        $args = $this->resolveParameters($rf->getParameters(), $container);
        $fact(...$args);
      }
    }
  }

  protected function buildFactsIA(ContainerBuilder $container): FactsIAInterface {
    $objects = [];
    $decoratorClasses = [];
    // @todo Search only specific namespaces.
    foreach ($container->getDefinitions() as $definition) {
      $class = $definition->getClass();
      if ($class === NULL) {
        continue;
      }
      $rc = new ClassReflection($class);
      $attributes = $rc->getAttributes(ServicesDiscovery::class);
      if (!$attributes) {
        continue;
      }
      if (!$rc->isInstantiable()) {
        throw new \RuntimeException("Class $class is not instantiable.");
      }
      $parameters = $rc->getParameters();
      if (!$parameters) {
        $objects[$class] = new $class();
        continue;
      }
      if (\count($parameters) > 1) {
        throw new \RuntimeException("Class $class has more than one constructor parameter.");
      }
      // This might be a decorator.
      $type = $parameters[0]->getType();
      if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
        throw new \RuntimeException(sprintf(
          "Unexpected type %s on parameter %s.",
          $type,
          MessageUtil::formatReflector($parameters[0]),
        ));
      }
      $decoratedClass = $type->getName();
      if (!\is_a($class, $decoratedClass, TRUE)) {
        throw new \RuntimeException(sprintf(
          "Class %s is not a decorator.",
          $class,
        ));
      }
      $decoratorClasses[$decoratedClass][] = $class;
    }
    $classes = ReflectionClassesIA_Concat::fromCandidateObjects($objects);
    $inspector = FactoryInspector_Concat::fromCandidateObjects($objects);
    foreach ($decoratorClasses[ReflectionClassesIAInterface::class] ?? [] as $decoratorClass) {
      $classes = new $decoratorClass($classes);
    }
    foreach ($decoratorClasses[FactoryInspectorInterface::class] ?? [] as $decoratorClass) {
      $inspector = new $decoratorClass($inspector);
    }
    return new FactsIA_InspectFactories($classes, $inspector);
  }

  protected function resolveParameters(array $parameters, ContainerBuilder $container): array {
    return \array_map(
      static fn (\ReflectionParameter $param) => match ((string) $param->getType()) {
        ContainerBuilder::class => $container,
        default => throw new \RuntimeException(sprintf(
          "Cannot resolve %s.",
          MessageUtil::formatReflector($param),
        )),
      },
      $parameters,
    );
  }

}
