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
