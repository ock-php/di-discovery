<?php

/**
 * @file
 */

declare(strict_types = 1);

namespace Ock\DID\Tests\Fixtures\Services;

interface WordLookupInterface {

  public function lookup(string $string): string;

}
