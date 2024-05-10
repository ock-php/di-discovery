<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

class ParamToCTV {

  /**
   * Creates a default composition.
   *
   * @return \Donquixote\DID\ParamToCTV\ParamToCTVInterface
   */
  public static function create(): ParamToCTVInterface {
    return new ParamToCTV_Chain([
      new ParamToCTV_Attribute_GetService(),
      new ParamToCTV_Attribute_CallService(),
      new ParamToCTV_Attribute_CallServiceMethod(),
    ]);
  }

}
