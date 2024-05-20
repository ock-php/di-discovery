## Single service

Value definition:

```php
use Ock\DID\ValueDefinition\ValueDefinition_GetService;

return new ValueDefinition_GetService('service_id'); 
```

Generated code:

```php
return $container->get('service_id');
```

Flattened definition generated code:

```php
use Ock\DID\FlatService;

return FlatService::get($container->get('service_id'));
```
