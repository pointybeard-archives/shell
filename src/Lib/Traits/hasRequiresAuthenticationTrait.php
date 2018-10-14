<?php

namespace Symphony\Shell\Lib\Traits;

use Symphony\Shell\Lib;

/**
 * Use this trait to force authentication for a command
 */
trait hasRequiresAuthenticationTrait
{
    public function authenticate()
    {
        if (!Lib\Shell::instance()->isLoggedIn()) {

            // Let the user know they are not authenticated.
            (new Lib\Message("NOTICE: This command requires authentication."))
                ->foreground("yellow")
                ->prependDate(false)
                ->appendNewLine(true)
                ->display()
            ;

            // If we got this far, it means the user did not provide the
            // -u or -t argments. We should still give them an opportunity
            // to provide authentication.
            $username = Lib\Utils::promptForInput("username");
            $password = Lib\Utils::promptForInput("password", true);

            // Try to log the user in with the credential supplied.
            if (false == Lib\Shell::instance()->login($username, $password)) {
                throw new Lib\Exceptions\AuthenticationRequiredException(
                    "Username and/or password supplied are invalid."
                );
            }
        }
    }
}
