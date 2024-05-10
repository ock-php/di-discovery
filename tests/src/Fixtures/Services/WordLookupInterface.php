<?php

/**
 * @file
 */

declare(strict_types = 1);

namespace Donquixote\DID\Tests\Fixtures\Services;

interface WordLookupInterface {

  public function lookup(string $string): string;

}
