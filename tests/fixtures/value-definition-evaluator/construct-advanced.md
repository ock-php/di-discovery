## Construct advanced

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;

return new ValueDefinition_Construct(C::class, [
  new ValueDefinition_GetService('example_service'),
  new ValueDefinition_Call([C::class, 'create'], [
    new ValueDefinition_GetService('other_service'),
  ]),
  new ValueDefinition_Call(
    [new ValueDefinition_Construct(C::class), 'foo'],
    [5],
  ),
  new ValueDefinition_Call(
    [new ValueDefinition_GetService('service_with_method'), 'foo'],
    [],
  ),
  new ValueDefinition_CallObjectMethod(
    new ValueDefinition_GetService('service_with_method'),
    'theMethod',
    [7],
  ),
]); 
```

Serialized value:

```php
return unserialize('O:31:"Donquixote\\DID\\Tests\\Fixtures\\C":1:{s:6:"values";a:5:{i:0;O:43:"Donquixote\\DID\\Tests\\Fixtures\\GenericObject":1:{s:6:"values";a:1:{s:2:"id";s:15:"example_service";}}i:1;O:43:"Donquixote\\DID\\Tests\\Fixtures\\GenericObject":1:{s:6:"values";a:3:{s:5:"class";s:31:"Donquixote\\DID\\Tests\\Fixtures\\C";s:6:"method";s:6:"create";s:4:"args";a:1:{i:0;O:43:"Donquixote\\DID\\Tests\\Fixtures\\GenericObject":1:{s:6:"values";a:1:{s:2:"id";s:13:"other_service";}}}}}i:2;O:43:"Donquixote\\DID\\Tests\\Fixtures\\GenericObject":1:{s:6:"values";a:3:{s:6:"object";O:31:"Donquixote\\DID\\Tests\\Fixtures\\C":1:{s:6:"values";a:0:{}}s:6:"method";s:3:"foo";s:4:"args";a:1:{i:0;i:5;}}}i:3;O:43:"Donquixote\\DID\\Tests\\Fixtures\\GenericObject":1:{s:6:"values";a:3:{s:6:"object";O:43:"Donquixote\\DID\\Tests\\Fixtures\\GenericObject":1:{s:6:"values";a:1:{s:2:"id";s:19:"service_with_method";}}s:6:"method";s:3:"foo";s:4:"args";a:0:{}}}i:4;O:43:"Donquixote\\DID\\Tests\\Fixtures\\GenericObject":1:{s:6:"values";a:3:{s:6:"object";O:43:"Donquixote\\DID\\Tests\\Fixtures\\GenericObject":1:{s:6:"values";a:1:{s:2:"id";s:19:"service_with_method";}}s:6:"method";s:9:"theMethod";s:4:"args";a:1:{i:0;i:7;}}}}}');
```
