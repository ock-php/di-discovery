<?php

declare(strict_types = 1);

namespace Donquixote\DID\Tests\Fixtures\Services;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;

#[Service]
class WordLookup implements WordLookupInterface {

  public function __construct(
    #[GetService]
    private readonly SiteConfig $siteConfig,
  ) {}

  public function lookup(string $string): string {
    // Imagine this is a real lookup.
    return '(' . $string . ':' . $this->siteConfig->getCurrentLangcode() . ')';
  }

}
