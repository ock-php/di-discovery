## Call callable service args

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;

return new ValueDefinition_CallObjectMethod(
  new ValueDefinition_GetService('some_service'),
  'foo',
  [
    'x',
    'y',
    new ValueDefinition_GetService('other_service'),
  ],
);
```

Generated code:

```php
return $container->get('some_service')->foo('x', 'y', $container->get(
  'other_service',
));
```

Flattened definition generated code:

```php
use Donquixote\DID\FlatService;

return FlatService::callDynamic(
  4,
  ['op' => 'array', 'array' => [4, 5]],
  'x',
  'y',
  $container->get('other_service'),
  $container->get('some_service'),
  'foo',
);
```
