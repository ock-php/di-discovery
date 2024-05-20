## Call callable service

Value definition:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\ValueDefinition\ValueDefinition_Call;
use Ock\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Ock\DID\ValueDefinition\ValueDefinition_GetService;

return new ValueDefinition_CallObjectMethod(
  new ValueDefinition_GetService('some_service'),
  'foo',
  [],
);
```

Generated code:

```php
return $container->get('some_service')->foo();
```

Flattened definition generated code:

```php
use Ock\DID\FlatService;

return FlatService::callDynamic(
  1,
  ['op' => 'array', 'array' => [1, 2]],
  $container->get('some_service'),
  'foo',
);
```
