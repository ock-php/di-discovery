<?php

declare(strict_types = 1);

namespace Ock\DID\ParamToEgg;

use Ock\DID\ParamToEgg\ParamToEgg_Attribute_GetService;
use Ock\Egg\ParamToEgg\ParamToEgg_Chain;
use Ock\Egg\ParamToEgg\ParamToEggInterface;

class ParamToEgg {

  /**
   * Creates a default composition.
   *
   * @return \Ock\Egg\ParamToEgg\ParamToEggInterface
   */
  public static function create(): ParamToEggInterface {
    return new ParamToEgg_Chain([
      new ParamToEgg_Attribute_GetService(),
      new ParamToEgg_Attribute_CallService(),
      new ParamToEgg_Attribute_CallServiceMethod(),
    ]);
  }

}
