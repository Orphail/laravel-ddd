<?php

/**  */

use Src\Common\Exceptions\UnauthorizedUserException;

if (! function_exists('authorize')) {
    /* @throws UnauthorizedUserException */
    function authorize($ability, $policy, $arguments = []): bool
    {
        if ($policy->{$ability}(...$arguments)) {
            return true;
        }
        throw new UnauthorizedUserException();
    }
}
