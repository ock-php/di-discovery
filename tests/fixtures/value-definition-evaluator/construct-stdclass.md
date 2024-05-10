## Construct stdclass

Value definition:

```php
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;

return new ValueDefinition_Construct(\stdClass::class); 
```

Serialized value:

```php
return unserialize('O:8:"stdClass":0:{}');
```
