## Call function

Value definition:

```php
use Donquixote\DID\Tests\Fixtures\C;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;

return new ValueDefinition_Call('strlen', ['hello']); 
```

Generated code:

```php
return \strlen('hello');
```
