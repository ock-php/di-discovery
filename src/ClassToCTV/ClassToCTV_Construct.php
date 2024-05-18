<?php

declare(strict_types = 1);

namespace Donquixote\DID\ClassToCTV;

use Donquixote\DID\ParamToCTV\ParamToCTVInterface;
use Ock\Egg\Egg\Egg_Construct;
use Ock\Egg\Egg\EggInterface;
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
    $argEggs = [];
    foreach ($reflectionClass->getConstructor()?->getParameters() ?? [] as $parameter) {
      $argEggs[] = $this->paramToCTV->paramGetCTV($parameter);
    }
    return new Egg_Construct(
      $reflectionClass->getName(),
      $argEggs,
    );
  }

}
