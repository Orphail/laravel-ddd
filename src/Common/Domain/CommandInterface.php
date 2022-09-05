<?php

namespace Src\Common\Domain;

use Src\Common\Domain\Exceptions\UnauthorizedUserException;

interface CommandInterface
{
    /**
     * @throws UnauthorizedUserException
     */
    public function execute();
}