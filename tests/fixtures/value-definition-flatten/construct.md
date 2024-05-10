## Construct

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;

return new ValueDefinition_Construct(C::class, [
  5,
  new ValueDefinition_GetService('some_service'),
]); 
```

Generated code:

```php
use Donquixote\DID\Tests\Fixtures\C;

return new C(5, $container->get('some_service'));
```

The flattened definition is identical.
