<?php

declare(strict_types = 1);

/**
 * @var string $php
 *   Original php snippet.
 */

use Ock\CodegenTools\Util\CodeFormatUtil;
use Ock\DID\ValueDefinitionToPhp\ValueDefinitionToPhp;

$definition = eval($php);
$expression = (new ValueDefinitionToPhp())->generate($definition);
/** @noinspection PhpUnhandledExceptionInspection */
$snippet = CodeFormatUtil::formatAsSnippet('return ' . $expression . ';');

?>

Generated code:

```php
<?= $snippet . "\n" ?>
```
