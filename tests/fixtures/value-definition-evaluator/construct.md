## Construct

Value definition:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\ValueDefinition\ValueDefinition_Construct;

return new ValueDefinition_Construct(C::class, [5]); 
```

Serialized value:

```php
return unserialize('O:24:"Ock\\DID\\Tests\\Fixtures\\C":1:{s:6:"values";a:1:{i:0;i:5;}}');
```
