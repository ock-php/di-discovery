## Single container

Value definition:

```php
use Donquixote\DID\ValueDefinition\ValueDefinition_GetContainer;

return new ValueDefinition_GetContainer(); 
```

Generated code:

```php
return $container;
```

Flattened definition generated code:

```php
use Donquixote\DID\FlatService;

return FlatService::get($container);
```
