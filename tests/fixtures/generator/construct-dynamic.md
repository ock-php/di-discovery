## Construct dynamic

Value definition:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\ValueDefinition\ValueDefinition_Call;
use Ock\DID\ValueDefinition\ValueDefinition_Construct;

return new ValueDefinition_Construct(
  new ValueDefinition_Call([C::class, 'getClassName']),
  [5],
); 
```

Generated code:

```php
use Ock\DID\Tests\Fixtures\C;

return new (C::getClassName())(5);
```
