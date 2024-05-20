<?php

declare(strict_types = 1);

namespace Ock\DID\Tests\Fixtures\Services;

use Ock\DID\Attribute\Service;

#[Service]
class SiteConfig {

  public function getCurrentLangcode(): string {
    return 'fr';
  }

}
