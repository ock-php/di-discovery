## Single service

Value definition:

```php
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;

return new ValueDefinition_GetService('service_id'); 
```

Generated code:

```php
return $container->get('service_id');
```

Flattened definition generated code:

```php
use Donquixote\DID\FlatService;

return FlatService::get($container->get('service_id'));
```
