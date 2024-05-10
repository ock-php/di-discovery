<?php

declare(strict_types = 1);

namespace Donquixote\DID\Exception;

use Psr\Container\NotFoundExceptionInterface;

class ServiceNotFoundException extends \Exception implements NotFoundExceptionInterface {

}
