## Call dynamic 3

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\Tests\Fixtures\GenericObject;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;

return new ValueDefinition_Call([
  new ValueDefinition_Construct(GenericObject::class),
  new ValueDefinition_Call([C::class, 'getMethodName']),
]); 
```

Generated code:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\Tests\Fixtures\GenericObject;

return (new GenericObject())->{C::getMethodName()}();
```
