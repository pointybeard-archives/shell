<?php
namespace Shell\Lib;

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
    public static function promptForInput($prompt, $silent = false, $default = null, \Closure $validator = null)
    {
        if ($silent == true && !self::canInvokeBash()) {
            throw new \Exception("bash cannot be invoked from PHP. 'silent' flag cannot be used.");
        }

        if(!($prompt instanceof Message)) {
            $prompt = new Message($prompt);
        }

        $prompt->message(sprintf(
            "%s%s: ", $prompt->message, (!is_null($default) ? " [{$default}]" : null)
        ));

        do{
            $prompt->display();
            
            if($silent) {
                $command = "/usr/bin/env bash -c 'read -s in && echo \$in'";
                $input = shell_exec($command);
                echo PHP_EOL;

            } else {
                $input = fgets(STDIN, 256);
            }

            $input = trim($input);
            if(strlen(trim($input)) == 0 && !is_null($default)){
                $input = $default;
            }

        }while($validator instanceof \Closure && !$validator($input));

        return $input;
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
