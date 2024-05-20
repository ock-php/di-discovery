## Generate container

Executed PHP:

```php
use Ock\CodegenTools\Util\CodeFormatUtil;
use Ock\DID\Exception\ServiceNotFoundException;
use Ock\DID\Tests\Fixtures\Services\EntryPoints;
use PHPUnit\Framework\Assert;
use Psr\Container\ContainerInterface;

$generator = new \Ock\DID\ValueDefinitionToPhp\ValueDefinitionToPhp('$this');

$definitions = EntryPoints::getServiceDefinitionList()->getDefinitions();
$getServicePhp = '';
$hasServicePhp = '';
foreach ($definitions as $definition) {
  $getServicePhp .= var_export($definition->id, true) . ' => ' . $generator->generate($definition->valueDefinition) . ",\n";
  $hasServicePhp .= var_export($definition->id, true) . ' => true' . ",\n";
}
$getServicePhp .= 'default => $this->fail($id)' . ",\n";
$getServicePhp = "match (\$id) {\n$getServicePhp}";
$hasServicePhp = "isset([\n$hasServicePhp][\$id])";

$containerInterface = ContainerInterface::class;
$notFoundExceptionClass = ServiceNotFoundException::class;

$expression = <<<EOT
new class implements \\$containerInterface {

  private array \$cache = [];

  public function get(string \$id): mixed {
    return \$this->cache[\$id] ??= $getServicePhp;
  }

  public function has(string \$id): bool {
    return $hasServicePhp;
  }

  private function fail(string \$id): never {
    throw new \\$notFoundExceptionClass("Service '\$id' was not found.");
  }
}
EOT;

/** @var \Psr\Container\ContainerInterface $container */
$container = eval("return $expression;");

foreach ($definitions as $definition) {
  Assert::assertTrue($container->has($definition->id));
  $container->get($definition->id);
}

Assert::assertFalse($container->has('other'));

try {
  $container->get('other');
  Assert::fail('Missing exception.');
}
catch (ServiceNotFoundException) {}

return CodeFormatUtil::formatExpressionAsSnippet($expression);
```

Return value:

```php
use Ock\DID\Exception\ServiceNotFoundException;
use Ock\DID\Tests\Fixtures\Services\SiteConfig;
use Ock\DID\Tests\Fixtures\Services\Translator\Translator;
use Ock\DID\Tests\Fixtures\Services\WordLookup;
use Psr\Container\ContainerInterface;

return new class implements ContainerInterface {

  private array $cache = [];

  public function get(string $id): mixed {
    return $this->cache[$id] ??= match ($id) {
      'Ock\\DID\\Tests\\Fixtures\\Services\\SiteConfig' => new SiteConfig(),
      'Ock\\DID\\Tests\\Fixtures\\Services\\Translator\\Translator' => new Translator(
        $this->get('Ock\\DID\\Tests\\Fixtures\\Services\\WordLookupInterface'),
      ),
      'Ock\\DID\\Tests\\Fixtures\\Services\\WordLookupInterface' => new WordLookup(
        $this->get('Ock\\DID\\Tests\\Fixtures\\Services\\SiteConfig'),
      ),
      default => $this->fail($id),
    };
  }

  public function has(string $id): bool {
    return isset([
      'Ock\\DID\\Tests\\Fixtures\\Services\\SiteConfig' => true,
      'Ock\\DID\\Tests\\Fixtures\\Services\\Translator\\Translator' => true,
      'Ock\\DID\\Tests\\Fixtures\\Services\\WordLookupInterface' => true,
    ][$id]);
  }

  private function fail(string $id): never {
    throw new ServiceNotFoundException("Service '$id' was not found.");
  }
};
```
