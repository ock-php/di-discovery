<?php

declare(strict_types = 1);

/**
 * @var string $php
 *   Original php snippet.
 */

use Donquixote\CodegenTools\Util\CodeFormatUtil;
use Donquixote\DID\ValueDefinitionToPhp\ValueDefinitionToPhp;
use Donquixote\CodegenTools\Util\CodeGen;

$definition = eval($php);
$expression = (new ValueDefinitionToPhp())->generate($definition);
/** @noinspection PhpUnhandledExceptionInspection */
$snippet = CodeFormatUtil::formatAsSnippet('return ' . $expression . ';');

?>

Generated code:

```php
<?= $snippet . "\n" ?>
```
