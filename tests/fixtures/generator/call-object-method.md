## Call object method

Value definition:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\Tests\Fixtures\GenericObject;
use Ock\DID\ValueDefinition\ValueDefinition_Call;
use Ock\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Ock\DID\ValueDefinition\ValueDefinition_Construct;

return new ValueDefinition_CallObjectMethod(
  new ValueDefinition_Construct(GenericObject::class),
  new ValueDefinition_Call([C::class, 'getMethodName']),
  [5],
); 
```

Generated code:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\Tests\Fixtures\GenericObject;

return (new GenericObject())->{C::getMethodName()}(5);
```
