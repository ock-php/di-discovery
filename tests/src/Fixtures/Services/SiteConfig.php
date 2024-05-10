<?php

declare(strict_types = 1);

namespace Donquixote\DID\Tests\Fixtures\Services;

use Donquixote\DID\Attribute\Service;

#[Service]
class SiteConfig {

  public function getCurrentLangcode(): string {
    return 'fr';
  }

}
