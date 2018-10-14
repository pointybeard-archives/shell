<?php
namespace Symphony\Shell\Lib;

use Exception;
use GenericExceptionHandler;

class ExceptionHandler extends GenericExceptionHandler
{
    public static function initialise()
    {
        self::$enabled = true;

        // Symphony's exception handler is set twice. Call restore_error_handler 2
        // times to clear it out.
        restore_exception_handler();
        restore_exception_handler();
        set_exception_handler(array(__CLASS__, 'handler'));
    }

    public static function handler($e)
    {
        try {
            if (self::$enabled !== true) {
                return;
            }

            $class = __CLASS__;
            $exception_type = get_class($e);
            if (class_exists("{$exception_type}Handler") && method_exists("{$exception_type}Handler", 'render')) {
                $class = "{$exception_type}Handler";
            }

            echo call_user_func(array($class, 'render'), $e);
        } catch (Exception $e) {
            echo 'Looks like the Exception handler crapped out';
            print_r($e);
        }

        exit();
    }

    public static function render($e)
    {
        $lines = null;

        foreach (self::__nearByLines($e->getLine(), $e->getFile()) as $line => $string) {
            $lines .= sprintf(
                '%d: %s',
                ++$line,
                $string
            );
        }

        $trace = null;

        if (count($e->getTrace()) > 0) {
            foreach ($e->getTrace() as $t) {
                $trace .= sprintf(
                    '[%s:%d] %s%s%s();'."\n",
                    (isset($t['file']) ? $t['file'] : null),
                    (isset($t['line']) ? $t['line'] : null),
                    (isset($t['class']) ? $t['class'] : null),
                    (isset($t['type']) ? $t['type'] : null),
                    $t['function']
                );
            }
        }

        $queries = null;
        if (is_object(\Symphony::Database())) {
            $debug = \Symphony::Database()->debug();

            if (count($debug['query']) > 0) {
                foreach ($debug['query'] as $query) {
                    $queries .= sprintf(
                        '%s; [%01.4f]'."\n",
                        preg_replace('/[\r\n\t]+/', ' ', $query['query']),
                        (isset($query['time']) ? $query['time'] : null)
                    );
                }
            }
        }

        return sprintf(
            '%s: %s

An error occurred in %s around line %d
%s
%s
%s',

            ($e instanceof ErrorException ? GenericErrorHandler::$errorTypeStrings[$e->getSeverity()] : 'Fatal Error'),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $lines,
            (!is_null($trace) ? "Backtrace".PHP_EOL."===========================".PHP_EOL.$trace.PHP_EOL : null),
            (!is_null($queries) ? "Database Query Log".PHP_EOL."===========================".PHP_EOL.$queries.PHP_EOL : null)
        );
    }
}
