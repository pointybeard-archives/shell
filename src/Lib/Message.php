<?php
namespace Symphony\Shell\Lib;

class Message
{
    private $message = null;
    private $background = null;
    private $foreground = null;
    private $prependDate = false;
    private $dateFormat = null;
    private $appendNewLine = true;

    // Credit to JR from http://www.if-not-true-then-false.com/
    // for the code reponsible for colourising the messages
    private static $foregroundColours = [
        'black'         => '0;30',
        'red'           => '0;31',
        'green'         => '0;32',
        'brown'         => '0;33',
        'blue'          => '0;34',
        'purple'        => '0;35',
        'cyan'          => '0;36',
        'white'         => '1;37',
        'dark gray'     => '1;30',
        'light red'     => '1;31',
        'light green'   => '1;32',
        'yellow'        => '1;33',
        'light blue'    => '1;34',
        'light purple'  => '1;35',
        'light cyan'    => '1;36',
        'light gray'    => '0;37',
    ];

    private static $backgroundColours = [
        'black'         => '40',
        'red'           => '41',
        'green'         => '42',
        'yellow'        => '43',
        'blue'          => '44',
        'magenta'       => '45',
        'cyan'          => '46',
        'default'       => '49',
        'white'         => '107',
        'light gray'    => '47',
        'light red'     => '101',
        'light green'   => '102',
        'light yellow'  => '103',
        'light blue'    => '104',
        'light magenta' => '105',
        'light cyan'    => '106',
        'dark gray'     => '100',
    ];

    public function __get($name)
    {
        return $this->$name;
    }

    public function __construct($message = null)
    {
        $this->message = $message;

        // Seed the date format
        $this->dateFormat("H:i:s > ");
    }

    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    public function foreground($colour)
    {
        if (!is_null($colour) && !array_key_exists($colour, self::$foregroundColours)) {
            throw new \Exception('No such foreground colour `'.$colour.'`');
        }
        $this->foreground = $colour;
        return $this;
    }

    public function background($colour)
    {
        if (!is_null($colour) && !array_key_exists($colour, self::$backgroundColours)) {
            throw new \Exception('No such background colour `'.$colour.'`');
        }
        $this->background = $colour;
        return $this;
    }

    public function prependDate($prependDate)
    {
        $this->prependDate = $prependDate;
        return $this;
    }

    public function dateFormat($format)
    {
        $this->dateFormat = $format;
        return $this;
    }

    public function appendNewLine($appendNewLine)
    {
        $this->appendNewLine = $appendNewLine;
        return $this;
    }

    public function display($target=STDOUT)
    {
        return fputs($target, (string)$this);
    }

    public function __toString()
    {
        $message = null;

        if (!is_null($this->foreground)) {
            $message .= "\e[" . self::$foregroundColours[$this->foreground] . "m";
        }

        if (!is_null($this->background)) {
            $message .= "\e[" . self::$backgroundColours[$this->background] . "m";
        }

        // Add string and end coloring
        $message .=  $this->message . "\033[0m";

        return sprintf(
            '%s%s%s',
            ($this->prependDate ? gmdate($this->dateFormat) : null),
            (string)$message,
            ($this->appendNewLine ? PHP_EOL : null)
        );
    }
}
