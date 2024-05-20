## Construct advanced

Value definition:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\ValueDefinition\ValueDefinition_Call;
use Ock\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Ock\DID\ValueDefinition\ValueDefinition_Construct;
use Ock\DID\ValueDefinition\ValueDefinition_GetService;

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
use Ock\DID\Tests\Fixtures\C;

return new C(
  $container->get('example_service'),
  C::create($container->get('other_service')),
  (new C())->foo(5),
  [$container->get('service_with_method'), 'foo'](),
  $container->get('service_with_method')->theMethod(7),
);
```
