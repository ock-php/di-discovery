## Call object method

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\Tests\Fixtures\GenericObject;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;

return new ValueDefinition_CallObjectMethod(
  new ValueDefinition_Construct(GenericObject::class),
  new ValueDefinition_Call([C::class, 'getMethodName']),
  [5],
); 
```

Generated code:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\Tests\Fixtures\GenericObject;

return (new GenericObject())->{C::getMethodName()}(5);
```
