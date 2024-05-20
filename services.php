<?php

declare(strict_types = 1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
  $services = $container->services();

  $services->defaults()
    ->autowire()
    ->autoconfigure();

  // The path is relative to the package root where 'services.php' is located.
  $services->load('Ock\\DID\\', 'src/');
};

