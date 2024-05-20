## Call dynamic 2

Value definition:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\Tests\Fixtures\GenericObject;
use Ock\DID\ValueDefinition\ValueDefinition_Call;

return new ValueDefinition_Call([
  GenericObject::class,
  new ValueDefinition_Call([C::class, 'getMethodName']),
]); 
```

Generated code:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\Tests\Fixtures\GenericObject;

return GenericObject::{C::getMethodName()}();
```
