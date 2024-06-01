<?php

declare(strict_types = 1);

use Ock\DID\Symfony\DiscoveryPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container, ContainerBuilder $builder): void {
  $services = $container->services();

  $services->defaults()
    ->autowire()
    ->autoconfigure();

  $builder->addCompilerPass(
    new DiscoveryPass(),
    PassConfig::TYPE_BEFORE_OPTIMIZATION,
    999,
  );

  // The path is relative to the package root where 'services.php' is located.
  $services->load('Ock\\DID\\', 'src/');
};

