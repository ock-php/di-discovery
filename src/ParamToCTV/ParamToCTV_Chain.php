<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Ock\Egg\Egg\EggInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

#[AsAlias]
class ParamToCTV_Chain implements ParamToCTVInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\DID\ParamToCTV\ParamToCTVInterface[] $p2CTVs
   */
  public function __construct(
    #[AutowireIterator(ParamToCTVInterface::SERVICE_TAG)]
    private readonly iterable $p2CTVs,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?EggInterface {
    foreach ($this->p2CTVs as $paramToCTV) {
      $ctv = $paramToCTV->paramGetCTV($parameter);
      if ($ctv !== NULL) {
        return $ctv;
      }
    }
    return NULL;
  }

}
