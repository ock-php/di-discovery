## Call dynamic 2

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\Tests\Fixtures\GenericObject;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;

return new ValueDefinition_Call([
  GenericObject::class,
  new ValueDefinition_Call([C::class, 'getMethodName']),
]); 
```

Generated code:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\Tests\Fixtures\GenericObject;

return GenericObject::{C::getMethodName()}();
```
