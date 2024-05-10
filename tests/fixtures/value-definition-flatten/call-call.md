## Call call

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;

return new ValueDefinition_Call([C::class, 'create'], [
  new ValueDefinition_GetService('some_service'),
  new ValueDefinition_Call([C::class, 'create'], ['x']),
  new ValueDefinition_Call([
    new ValueDefinition_GetService('other_service'),
    'foo',
  ]),
]);
```

Generated code:

```php
use Donquixote\DID\Tests\Fixtures\C;

return C::create(
  $container->get('some_service'),
  C::create('x'),
  [$container->get('other_service'), 'foo'](),
);
```

Flattened definition generated code:

```php
use Donquixote\DID\FlatService;

return FlatService::call(
  ['Donquixote\\DID\\Tests\\Fixtures\\C', 'create'],
  3,
  $container->get('some_service'),
  ['op' => 'call', 'callback' => 3, 'args' => [4]],
  ['op' => 'call', 'callback' => 5, 'args' => []],
  ['op' => 'array', 'array' => [6, 7]],
  'x',
  ['op' => 'array', 'array' => [8, 9]],
  'Donquixote\\DID\\Tests\\Fixtures\\C',
  'create',
  $container->get('other_service'),
  'foo',
);
```
