## Construct dynamic

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;

return new ValueDefinition_Construct(
  new ValueDefinition_Call([C::class, 'getClassName']),
  [5],
); 
```

Generated code:

```php
use Donquixote\DID\Tests\Fixtures\C;

return new (C::getClassName())(5);
```
