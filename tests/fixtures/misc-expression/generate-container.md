## Generate container

Executed PHP:

```php
use Donquixote\CodegenTools\Util\CodeFormatUtil;use Donquixote\DID\Exception\ServiceNotFoundException;
use Donquixote\DID\ServiceDefinitionList\ServiceDefinitionList_Discovery;
use Donquixote\DID\Tests\Fixtures\Services\EntryPoints;
use Donquixote\CodegenTools\Util\CodeGen;
use PHPUnit\Framework\Assert;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

$generator = new \Donquixote\DID\ValueDefinitionToPhp\ValueDefinitionToPhp('$this');

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
use Donquixote\DID\Exception\ServiceNotFoundException;
use Donquixote\DID\Tests\Fixtures\Services\SiteConfig;
use Donquixote\DID\Tests\Fixtures\Services\Translator\Translator;
use Donquixote\DID\Tests\Fixtures\Services\WordLookup;
use Psr\Container\ContainerInterface;

return new class implements ContainerInterface {

  private array $cache = [];

  public function get(string $id): mixed {
    return $this->cache[$id] ??= match ($id) {
      'Donquixote\\DID\\Tests\\Fixtures\\Services\\SiteConfig' => new SiteConfig(),
      'Donquixote\\DID\\Tests\\Fixtures\\Services\\Translator\\Translator' => new Translator(
        $this->get(
          'Donquixote\\DID\\Tests\\Fixtures\\Services\\WordLookupInterface',
        ),
      ),
      'Donquixote\\DID\\Tests\\Fixtures\\Services\\WordLookupInterface' => new WordLookup(
        $this->get('Donquixote\\DID\\Tests\\Fixtures\\Services\\SiteConfig'),
      ),
      default => $this->fail($id),
    };
  }

  public function has(string $id): bool {
    return isset([
      'Donquixote\\DID\\Tests\\Fixtures\\Services\\SiteConfig' => true,
      'Donquixote\\DID\\Tests\\Fixtures\\Services\\Translator\\Translator' => true,
      'Donquixote\\DID\\Tests\\Fixtures\\Services\\WordLookupInterface' => true,
    ][$id]);
  }

  private function fail(string $id): never {
    throw new ServiceNotFoundException("Service '$id' was not found.");
  }
};
```
