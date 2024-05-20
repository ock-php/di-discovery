<?php

declare(strict_types = 1);

namespace Ock\DID\Tests\Fixtures\Services\Translator;

use Ock\DID\Attribute\Parameter\GetService;
use Ock\DID\Attribute\Service;
use Ock\DID\Tests\Fixtures\Services\WordLookupInterface;

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
