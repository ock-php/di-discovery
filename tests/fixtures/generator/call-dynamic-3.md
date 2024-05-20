## Call dynamic 3

Value definition:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\Tests\Fixtures\GenericObject;
use Ock\DID\ValueDefinition\ValueDefinition_Call;
use Ock\DID\ValueDefinition\ValueDefinition_Construct;

return new ValueDefinition_Call([
  new ValueDefinition_Construct(GenericObject::class),
  new ValueDefinition_Call([C::class, 'getMethodName']),
]); 
```

Generated code:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\Tests\Fixtures\GenericObject;

return (new GenericObject())->{C::getMethodName()}();
```
