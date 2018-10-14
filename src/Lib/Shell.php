<?php
namespace Symphony\Shell\Lib;

use pointybeard\ShellArgs\Lib\ArgumentIterator;

class Shell extends \Symphony
{
    private $_args;

    public static function instance()
    {
        if (!(self::$_instance instanceof Shell)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    protected function __construct()
    {
        try {
            parent::__construct();

            // We want to ignore the 'Headers Already Sent' exception
        } catch (\Exception $e) {

            // Should this not be a headers already sent exception, rethrow it
            if (!preg_match('@headers\s+already\s+sent@i', $e->getMessage())) {
                throw $e;
            }
        }

        // Set Shell extension specific handlers
        ExceptionHandler::initialise();
        ErrorHandler::initialise();

        $this->_args = new ArgumentIterator();
    }

    public function __get($name)
    {
        if (!isset($this->{"_{$name}"})) {
            return false;
        }

        return $this->{"_{$name}"};
    }

    public static function message($string, $includeDate = true, $addNewLine = true, $foregroundColour = null, $backgroundColour = null)
    {
        return (new Message($string))
            ->prependDate($includeDate)
            ->dateFormat('G:i:s > ')
            ->appendNewLine($addNewLine)
            ->foreground($foregroundColour)
            ->background($backgroundColour)
            ->display();
    }

    public static function error($string, $exit = true)
    {
        self::message("ERROR: $string", false, true, "red");
        if ($exit == true) {
            exit(1);
        }
    }

    public static function warning($string)
    {
        self::message("WARNING: $string", false, true, "yellow");
    }
}
