<?php

declare(strict_types = 1);

namespace Donquixote\DID\Tests\Util;

use Donquixote\DID\Tests\Fixtures\GenericObject;
use Psr\Container\ContainerInterface;

class DummyContainer implements ContainerInterface {

  /**
   * {@inheritdoc}
   */
  public function get(string $id): GenericObject {
    return new GenericObject(id: $id);
  }

  /**
   * {@inheritdoc}
   */
  public function has(string $id): bool {
    return TRUE;
  }

}
