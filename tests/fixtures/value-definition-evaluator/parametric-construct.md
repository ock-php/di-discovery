## Parametric construct

Value definition:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\ValueDefinition\ValueDefinition_Call;
use Ock\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Ock\DID\ValueDefinition\ValueDefinition_Construct;
use Ock\DID\ValueDefinition\ValueDefinition_GetArgument;
use Ock\DID\ValueDefinition\ValueDefinition_GetService;
use Ock\DID\ValueDefinition\ValueDefinition_Parametric;

return new ValueDefinition_Call(
  new ValueDefinition_Parametric(
    new ValueDefinition_Construct(C::class, [
      5,
      new ValueDefinition_GetService('some_service'),
      new ValueDefinition_GetArgument(),
      new ValueDefinition_CallObjectMethod(
        new ValueDefinition_GetService('other_service'),
        'foo',
        [new ValueDefinition_GetArgument(1)],
      ),
    ]),
  ),
  ['a', 'b'],
);
```

Serialized value:

```php
return unserialize('O:24:"Ock\\DID\\Tests\\Fixtures\\C":1:{s:6:"values";a:4:{i:0;i:5;i:1;O:36:"Ock\\DID\\Tests\\Fixtures\\GenericObject":1:{s:6:"values";a:1:{s:2:"id";s:12:"some_service";}}i:2;s:1:"a";i:3;O:36:"Ock\\DID\\Tests\\Fixtures\\GenericObject":1:{s:6:"values";a:3:{s:6:"object";O:36:"Ock\\DID\\Tests\\Fixtures\\GenericObject":1:{s:6:"values";a:1:{s:2:"id";s:13:"other_service";}}s:6:"method";s:3:"foo";s:4:"args";a:1:{i:0;s:1:"b";}}}}}');
```
