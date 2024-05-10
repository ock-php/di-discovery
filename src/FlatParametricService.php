<?php

declare(strict_types = 1);

namespace Donquixote\DID;

class FlatParametricService {

  private array $serviceArgs;

  public function __construct(
    mixed ...$args,
  ) {
    $this->serviceArgs = $args;
  }

  public function __invoke(mixed ...$callArgs) {
    $args = $this->serviceArgs;
    foreach ($args as $i => $serviceArg) {
      if (is_array($serviceArg) && ($serviceArg['op'] ?? NULL) === 'arg') {
        $args[$i] = ['op' => 'value', 'value' => $callArgs[$serviceArg['position']]];
      }
    }
    $unpackedArgs = FlatService::unpackArgs(1, $args);
    return $unpackedArgs[0];
  }

}
