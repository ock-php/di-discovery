<?php

declare(strict_types = 1);

namespace Donquixote\DID\ClassToCTV;

use Ock\Egg\Egg\Egg_Construct;
use Ock\Egg\Egg\EggInterface;
use Donquixote\DID\ParamToCTV\ParamToCTVInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias]
class ClassToCTV_Construct implements ClassToCTVInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\DID\ParamToCTV\ParamToCTVInterface $paramToCTV
   */
  public function __construct(
    private readonly ParamToCTVInterface $paramToCTV,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function classGetCTV(\ReflectionClass $reflectionClass): EggInterface {
    $argCTVs = [];
    foreach ($reflectionClass->getConstructor()?->getParameters() ?? [] as $parameter) {
      $argCTVs[] = $this->paramToCTV->paramGetCTV($parameter);
    }
    return new Egg_Construct(
      $reflectionClass->getName(),
      $argCTVs,
    );
  }

}
