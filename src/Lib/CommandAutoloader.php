<?php
namespace Symphony\Shell\Lib;

final class CommandAutoloader
{
    public static function init()
    {
        // This custom autoloader checks the WORKSPACE/bin/ directory for a
        // matching command. We *could* just use the PSR4 autoloader, however
        // this will allow autloading from a dynamically set WORKSPACE folder
        spl_autoload_register(function ($className) {
            if (!preg_match('/^Symphony\\\\Shell\\\\Command\\\\Workspace\\\\(.+)$/i', $className, $matches)) {
                return;
            }

            $path = WORKSPACE . DIRECTORY_SEPARATOR . "bin";
            $file = str_replace('\\', '/', $matches[1]) . ".php";
            if (is_readable($path.DIRECTORY_SEPARATOR.$file)) {
                require $path.DIRECTORY_SEPARATOR.$file;
            }
        });

        // Autoload commands in an extensions /bin folder
        spl_autoload_register(function ($className) {
            if (preg_match_all('/^Symphony\\\\Shell\\\\Command\\\\([^\\\\]+)\\\\(.+)$/i', $className, $matches) == 1) {
                $command = $matches[2][0];
                $extension = $matches[1][0];
                $file = EXTENSIONS."/{$extension}/bin/{$command}";
                if (is_readable($file)) {
                    require $file;
                }
            }
        });

        // Autloader for Extension driver classes
        spl_autoload_register(function ($className) {
            if (!preg_match_all('/^Extension_(.*)$/i', $className, $matches)) {
                return;
            }

            $extension = strtolower($matches[1][0]);
            $file = EXTENSIONS.DIRECTORY_SEPARATOR.$extension.DIRECTORY_SEPARATOR."extension.driver.php";
            if (is_readable($file)) {
                require $file;
            }
        });
    }
}
