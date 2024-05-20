## Call

Value definition:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\ValueDefinition\ValueDefinition_Call;
use Ock\DID\ValueDefinition\ValueDefinition_GetService;

return new ValueDefinition_Call([C::class, 'create'], [
  5,
  new ValueDefinition_GetService('some_service'),
]); 
```

Generated code:

```php
use Ock\DID\Tests\Fixtures\C;

return C::create(5, $container->get('some_service'));
```

The flattened definition is identical.
