## Construct

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;

return new ValueDefinition_Construct(C::class, [5]); 
```

Serialized value:

```php
return unserialize('O:31:"Donquixote\\DID\\Tests\\Fixtures\\C":1:{s:6:"values";a:1:{i:0;i:5;}}');
```
