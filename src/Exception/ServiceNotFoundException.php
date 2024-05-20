<?php

declare(strict_types = 1);

namespace Ock\DID\Exception;

use Psr\Container\NotFoundExceptionInterface;

class ServiceNotFoundException extends \Exception implements NotFoundExceptionInterface {

}
