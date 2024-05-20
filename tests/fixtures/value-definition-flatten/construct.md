## Construct

Value definition:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\ValueDefinition\ValueDefinition_Construct;
use Ock\DID\ValueDefinition\ValueDefinition_GetService;

return new ValueDefinition_Construct(C::class, [
  5,
  new ValueDefinition_GetService('some_service'),
]); 
```

Generated code:

```php
use Ock\DID\Tests\Fixtures\C;

return new C(5, $container->get('some_service'));
```

The flattened definition is identical.
