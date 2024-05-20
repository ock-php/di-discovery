## Call function

Value definition:

```php
use Ock\DID\Tests\Fixtures\C;
use Ock\DID\ValueDefinition\ValueDefinition_Call;

return new ValueDefinition_Call('strlen', ['hello']); 
```

Generated code:

```php
return \strlen('hello');
```
