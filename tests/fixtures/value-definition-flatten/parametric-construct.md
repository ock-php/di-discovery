## Parametric construct

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetArgument;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;
use Donquixote\DID\ValueDefinition\ValueDefinition_Parametric;

return new ValueDefinition_Parametric(
  new ValueDefinition_Construct(C::class, [
    5,
    new ValueDefinition_GetService('some_service'),
    new ValueDefinition_GetArgument(),
    new ValueDefinition_CallObjectMethod(
      new ValueDefinition_GetService('other_service'),
      'foo',
      [
        new ValueDefinition_GetArgument(1),
      ],
    ),
  ]),
); 
```

Generated code:

```php
use Donquixote\DID\Tests\Fixtures\C;

return static fn (...$args) => new C(
  5,
  $container->get('some_service'),
  $args[0],
  $container->get('other_service')->foo($args[1]),
);
```

Flattened definition generated code:

```php
use Donquixote\DID\FlatService;

return FlatService::parametric(
  ['op' => 'construct', 'class' => 1, 'args' => [2, 3, 4, 5]],
  'Donquixote\\DID\\Tests\\Fixtures\\C',
  5,
  $container->get('some_service'),
  ['op' => 'arg', 'position' => 0],
  ['op' => 'call', 'callback' => 6, 'args' => [7]],
  ['op' => 'array', 'array' => [8, 9]],
  ['op' => 'arg', 'position' => 1],
  $container->get('other_service'),
  'foo',
);
```
