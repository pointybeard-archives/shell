<?php
namespace Shell\lib;

class Utils
{
    /**
     * This function waits for input on STDIN. Support silent input by setting $silent=true
     * However this requires bash. If bash is not available, then it will fallback to the "non-silent"
     * method.
     *
     * Credit to Troels Knak-Nielsen
     * (http://www.sitepoint.com/interactive-cli-password-prompt-in-php/) for
     * inspiring most of this code.
     *
     * @param  string $prompt
     *                        This is displayed before reading any input.
     * @param  bool   $silent
     *                        Turns off echoing of input to CLI. Useful for passwords. Only works if bash is avilable.
     * @return string
     */
    public static function promptForInput($prompt, $silent = false)
    {
        if ($silent == true && self::canInvokeBash()) {
            $command = "/usr/bin/env bash -c 'read -s -p \""
              .addslashes($prompt)
              ."\" mypassword && echo \$mypassword'";
            $password = shell_exec($command);
            echo PHP_EOL;
        } else {
            fputs(STDOUT, $prompt);
            $password = fgets(STDIN, 256);
        }

        return trim($password);
    }

    /**
     * Checks if bash can be invoked.
     *
     * Credit to Troels Knak-Nielsen
     * (http://www.sitepoint.com/interactive-cli-password-prompt-in-php/) for
     * inspiring this code.
     *
     * @return bool
     */
    public static function canInvokeBash()
    {
        return (strcmp(trim(shell_exec("/usr/bin/env bash -c 'echo OK'")), 'OK') === 0);
    }
}
