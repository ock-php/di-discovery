<?php

declare(strict_types = 1);

namespace Donquixote\DID\Tests\Fixtures\Services\Translator;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Donquixote\DID\Tests\Fixtures\Services\WordLookupInterface;

#[Service]
class Translator {

  public function __construct(
    #[GetService]
    private readonly WordLookupInterface $wordLookup,
  ) {}

  public function translate(string $string): string {
    return $this->wordLookup->lookup($string);
  }

}
