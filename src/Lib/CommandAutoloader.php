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
            $file = str_replace('\\', '/', $matches[1]);
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

    public static function fetch()
    {
        $commands = [];

        $path = realpath(WORKSPACE . '/bin');
        if (is_dir($path) && is_readable($path)) {
            foreach (new \DirectoryIterator($path) as $f) {
                if ($f->isDot()) {
                    continue;
                }
                $commands[] = "workspace/" . $f->getFilename();
            }
        }

        foreach (new \DirectoryIterator(EXTENSIONS) as $d) {
            if ($d->isDot() || !$d->isDir() || !is_dir($d->getPathname() . '/bin')) {
                continue;
            }

            foreach (new \DirectoryIterator($d->getPathname() . '/bin') as $f) {
                if ($f->isDot() || !preg_match_all('/extensions\/([^\/]+)\/bin\/([^\.\/]+)$/i', $f->getPathname(), $matches, PREG_SET_ORDER)) {
                    continue;
                }
                list(, $extension, $command) = $matches[0];

                // Skip over the core 'symphony' commands as this should be
                // inaccessable
                if ($extension == 'shell' && $command == 'symphony') {
                    continue;
                }

                $commands[] = "{$extension}/{$command}";
            }
        }

        return $commands;
    }
}
