## Construct

Value definition:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\ValueDefinition\ValueDefinition_Construct;

return new ValueDefinition_Construct(
  C::class,
  [5],
); 
```

Generated code:

```php
use Ock\DID\Tests\Fixtures\C;

return new C(5);
```
