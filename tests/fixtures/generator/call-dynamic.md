## Call dynamic

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;

return new ValueDefinition_Call([
  new ValueDefinition_Call([C::class, 'getClassName']),
  new ValueDefinition_Call([C::class, 'getMethodName']),
]); 
```

Generated code:

```php
use Donquixote\DID\Tests\Fixtures\C;

return [C::getClassName(), C::getMethodName()]();
```
