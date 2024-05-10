## Construct

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;

return new ValueDefinition_Construct(
  C::class,
  [5],
); 
```

Generated code:

```php
use Donquixote\DID\Tests\Fixtures\C;

return new C(5);
```
