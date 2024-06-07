<?php

declare(strict_types=1);

namespace Ock\DID\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * Tests the integration with symfony container.
 */
class SymfonyContainerTest extends TestCase {

  use RecordedTestTrait;

  /**
   * Tests the a symfony container using the mechanisms from this package.
   */
  public function testSymfonyContainer(): void {
    $container = new ContainerBuilder();
    $locator = new FileLocator(dirname(__DIR__, 2));
    $loader = new PhpFileLoader($container, $locator);
    $loader->load('services.php');
    $instanceof = [];
    $anonymousCount = 0;
    $services = new ServicesConfigurator(
      $container,
      $loader,
      $instanceof,
      NULL,
      $anonymousCount,
    );
    $services->defaults()
      ->autowire()
      ->autoconfigure();
    $services->load(__NAMESPACE__ . '\\Fixtures\\Services\\', 'tests/src/Fixtures/Services/');
    $container->compile();
    $export = [];
    foreach ($container->getServiceIds() as $id) {
      $service = $container->get($id);
      $definition = $container->findDefinition($id);
      static::assertIsObject($service);
      $instance_class = \get_class($service);
      $definition_class = $definition->getClass();
      if ($id === $instance_class && $instance_class === $definition_class) {
        $export[$id] = [];
      }
      elseif ($instance_class === $definition_class) {
        $export[$id] = ['class' => $definition_class];
      }
      elseif ($id === $instance_class) {
        $export[$id] = ['definition.class' => $definition_class];
      }
      else {
        $export[$id] = [
          'definition.class' => $definition_class,
          'instance.class' => $instance_class,
        ];
      }
      if (!$container->hasDefinition($id)) {
        // This is an alias.
        $export[$id]['is_alias'] = true;
      }
      $tags = $definition->getTags();
      if ($tags) {
        $export[$id]['tags'] = $tags;
      }
    }
    $this->assertAsRecorded($export, 'public services');
  }

}
