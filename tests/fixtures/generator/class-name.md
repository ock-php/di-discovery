## Class name

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\ValueDefinition\ValueDefinition_ClassName;

return new ValueDefinition_ClassName(C::class); 
```

Generated code:

```php
use Donquixote\DID\Tests\Fixtures\C;

return C::class;
```
