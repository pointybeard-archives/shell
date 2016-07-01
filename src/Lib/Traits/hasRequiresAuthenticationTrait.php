<?php

namespace Symphony\Shell\Lib\Traits;

use Symphony\Shell\Lib;

/**
 * Use this trait to force authentication for a command
 */
trait hasRequiresAuthenticationTrait
{
    function authenticate() {
        if(!Lib\Shell::instance()->isLoggedIn()){
            throw new Lib\Exceptions\AuthenticationRequiredException;
        }
    }
}
