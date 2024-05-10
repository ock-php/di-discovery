## Parametric construct

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetArgument;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;
use Donquixote\DID\ValueDefinition\ValueDefinition_Parametric;

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
return unserialize('O:31:"Donquixote\\DID\\Tests\\Fixtures\\C":1:{s:6:"values";a:4:{i:0;i:5;i:1;O:43:"Donquixote\\DID\\Tests\\Fixtures\\GenericObject":1:{s:6:"values";a:1:{s:2:"id";s:12:"some_service";}}i:2;s:1:"a";i:3;O:43:"Donquixote\\DID\\Tests\\Fixtures\\GenericObject":1:{s:6:"values";a:3:{s:6:"object";O:43:"Donquixote\\DID\\Tests\\Fixtures\\GenericObject":1:{s:6:"values";a:1:{s:2:"id";s:13:"other_service";}}s:6:"method";s:3:"foo";s:4:"args";a:1:{i:0;s:1:"b";}}}}}');
```
