## Construct advanced

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;

return new ValueDefinition_Construct(C::class, [
  new ValueDefinition_GetService('example_service'),
  new ValueDefinition_Call([C::class, 'create'], [
    new ValueDefinition_GetService('other_service'),
  ]),
  new ValueDefinition_Call(
    [
      new ValueDefinition_Construct(C::class),
      'foo',
    ],
    [
      5,
    ],
  ),
  new ValueDefinition_Call(
    [
      new ValueDefinition_GetService('service_with_method'),
      'foo',
    ],
    [],
  ),
  new ValueDefinition_CallObjectMethod(
    new ValueDefinition_GetService('service_with_method'),
    'theMethod',
    [7],
  ),
]); 
```

Generated code:

```php
use Donquixote\DID\Tests\Fixtures\C;

return new C(
  $container->get('example_service'),
  C::create($container->get('other_service')),
  (new C())->foo(5),
  [$container->get('service_with_method'), 'foo'](),
  $container->get('service_with_method')->theMethod(7),
);
```

Flattened definition generated code:

```php
use Donquixote\DID\FlatService;

return FlatService::construct(
  'Donquixote\\DID\\Tests\\Fixtures\\C',
  5,
  $container->get('example_service'),
  ['op' => 'call', 'callback' => 5, 'args' => [6]],
  ['op' => 'call', 'callback' => 7, 'args' => [8]],
  ['op' => 'call', 'callback' => 9, 'args' => []],
  ['op' => 'call', 'callback' => 10, 'args' => [11]],
  ['op' => 'array', 'array' => [12, 13]],
  $container->get('other_service'),
  ['op' => 'array', 'array' => [14, 15]],
  5,
  ['op' => 'array', 'array' => [16, 17]],
  ['op' => 'array', 'array' => [18, 19]],
  7,
  'Donquixote\\DID\\Tests\\Fixtures\\C',
  'create',
  ['op' => 'construct', 'class' => 20, 'args' => []],
  'foo',
  $container->get('service_with_method'),
  'foo',
  $container->get('service_with_method'),
  'theMethod',
  'Donquixote\\DID\\Tests\\Fixtures\\C',
);
```
