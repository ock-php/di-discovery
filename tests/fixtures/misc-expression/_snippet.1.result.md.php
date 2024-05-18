<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types = 1);

/**
 * @var string $title
 *   Human version of the file name.
 * @var string $php
 *   Original php snippet.
 * @var bool $fail
 *   TRUE if this example is expected to fail.
 */

$out = eval($php);

$langcode = $langs[1] ?? 'php';

?>

Return value:

```<?=$langcode . "\n" ?>
<?= $out . "\n" ?>
```
