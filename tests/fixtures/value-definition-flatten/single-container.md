## Single container

Value definition:

```php
use Ock\DID\ValueDefinition\ValueDefinition_GetContainer;

return new ValueDefinition_GetContainer(); 
```

Generated code:

```php
return $container;
```

Flattened definition generated code:

```php
use Ock\DID\FlatService;

return FlatService::get($container);
```
