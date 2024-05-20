## Class name

Value definition:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\ValueDefinition\ValueDefinition_ClassName;

return new ValueDefinition_ClassName(C::class); 
```

Generated code:

```php
use Ock\DID\Tests\Fixtures\C;

return C::class;
```
