## Parametric construct

Value definition:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\ValueDefinition\ValueDefinition_Call;
use Ock\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Ock\DID\ValueDefinition\ValueDefinition_Construct;
use Ock\DID\ValueDefinition\ValueDefinition_GetArgument;
use Ock\DID\ValueDefinition\ValueDefinition_GetService;
use Ock\DID\ValueDefinition\ValueDefinition_Parametric;

return new ValueDefinition_Call(
  new ValueDefinition_Parametric(
    new ValueDefinition_Construct(C::class, [
      5,
      new ValueDefinition_GetService('some_service'),
      new ValueDefinition_GetArgument(),
      new ValueDefinition_CallObjectMethod(
        new ValueDefinition_GetService('other_service'),
        'foo',
        [new ValueDefinition_GetArgument(1)],
      ),
    ]),
  ),
  ['a', 'b'],
);
```

Generated code:

```php
use Ock\DID\Tests\Fixtures\C;

return (static fn (...$args) => new C(
  5,
  $container->get('some_service'),
  $args[0],
  $container->get('other_service')->foo($args[1]),
))('a', 'b');
```
