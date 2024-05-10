## Call

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;

return new ValueDefinition_Call([C::class, 'create'], [
  5,
  new ValueDefinition_GetService('some_service'),
]); 
```

Generated code:

```php
use Donquixote\DID\Tests\Fixtures\C;

return C::create(5, $container->get('some_service'));
```

The flattened definition is identical.
