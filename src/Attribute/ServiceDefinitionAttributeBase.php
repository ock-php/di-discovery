<?php
declare(strict_types=1);

namespace Donquixote\DID\Attribute;

use Donquixote\DID\Attribute\Parameter\ServiceArgumentAttributeInterface;
use Donquixote\DID\Exception\DiscoveryException;
use Donquixote\DID\ServiceDefinition\ServiceDefinition;
use Donquixote\DID\Util\AttributesUtil;
use Donquixote\Helpers\Util\MessageUtil;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;
use Donquixote\DID\ValueDefinition\ValueDefinition_Parametric;

/**
 * Marks a class or method as a service.
 */
#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_CLASS|\Attribute::IS_REPEATABLE)]
abstract class ServiceDefinitionAttributeBase implements ServiceDefinitionAttributeInterface {

  /**
   * Constructor.
   *
   * @param string|null $serviceId
   *   The id, or NULL to use the interface name or the class name.
   * @param string|null $serviceIdSuffix
   *   Suffix to append to a service id.
   *   This allows to distinguish services that implement the same interface.
   */
  public function __construct(
    protected readonly ?string $serviceId = NULL,
    protected readonly ?string $serviceIdSuffix = NULL,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function onClass(\ReflectionClass $reflectionClass): ServiceDefinition {
    if (!$reflectionClass->isInstantiable()) {
      throw new DiscoveryException(sprintf(
        'Class %s is not instantiable.',
        $reflectionClass->getName(),
      ));
    }
    $type = ServiceDiscoveryUtil::classGetTypeName($reflectionClass);
    $realServiceId = $this->getRealServiceId($type);
    $parameters = $reflectionClass->getConstructor()?->getParameters() ?? [];
    $args = $this->buildArgumentDefinitions($parameters);
    $valueDefinition = $this->isParametric()
      ? new ValueDefinition_Parametric(
        $reflectionClass->getName(),
        $args,
      )
      : new ValueDefinition_Construct(
        $reflectionClass->getName(),
        $args,
      );
    return new ServiceDefinition(
      $realServiceId,
      $reflectionClass->getName(),
      $valueDefinition,
    );
  }

  public function onMethod(\ReflectionMethod $reflectionMethod): ServiceDefinition {
    if (!$reflectionMethod->isStatic()) {
      throw new DiscoveryException(sprintf(
        'Method %s is not static.',
        MessageUtil::formatReflector($reflectionMethod),
      ));
    }
    if ($reflectionMethod->isAbstract()) {
      throw new DiscoveryException(sprintf(
        'Method %s is abstract.',
        MessageUtil::formatReflector($reflectionMethod),
      ));
    }
    $type = ServiceDiscoveryUtil::functionGetTypeName($reflectionMethod);
    $parameters = $reflectionMethod->getParameters();
    $realServiceId = $this->getRealServiceId($type);
    $args = $this->buildArgumentDefinitions($parameters);
    $valueDefinition = $this->isParametric()
      ? new ValueDefinition_Parametric(
        [$reflectionMethod->getDeclaringClass()->getName(), $reflectionMethod->getName()],
        $args,
      )
      : new ValueDefinition_Call(
        [$reflectionMethod->getDeclaringClass()->getName(), $reflectionMethod->getName()],
        $args,
      );
    return new ServiceDefinition(
      $realServiceId,
      $type,
      $valueDefinition,
    );
  }

  /**
   * @param string $type
   *
   * @return string
   */
  protected function getRealServiceId(string $type): string {
    $serviceId = $this->serviceId ?? $type;
    if ($this->serviceIdSuffix !== NULL) {
      $serviceId .= '.' . $this->serviceIdSuffix;
    }
    if ($this->isParametric()) {
      $serviceId = 'get.' . $serviceId;
    }
    return $serviceId;
  }

  /**
   * @param array $parameters
   *
   * @return array
   *
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  protected function buildArgumentDefinitions(array $parameters): array {
    $args = [];
    foreach ($parameters as $parameter) {
      $args[] = AttributesUtil::requireSingle($parameter, ServiceArgumentAttributeInterface::class)
        ->getArgumentDefinition($parameter);
    }
    return $args;
  }

  /**
   * @return bool
   */
  abstract protected function isParametric(): bool;

}
