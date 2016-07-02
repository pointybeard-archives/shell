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
        try{
            parent::__construct();

        // We want to ignore the 'Headers Already Sent' exception
        } catch(\Exception $e) {

            // Should this not be a headers already sent exception, rethrow it
            if(!preg_match('@headers\s+already\s+sent@i', $e->getMessage())) {
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

    /**
     * Overload the Symphony::login function to bypass some code that
     * forces use of the Administration class (which of course is not
     * available in Shell). Hopefully this is fixed in the core Symphony code
     *
     */
    public static function login($username, $password, $isHash = false)
    {
        $username = self::Database()->cleanValue($username);
        $password = self::Database()->cleanValue($password);

        if (strlen(trim($username)) > 0 && strlen(trim($password)) > 0) {
            $author = \AuthorManager::fetch('id', 'ASC', 1, null, sprintf("
                `username` = '%s'
                ", $username
            ));

            if (!empty($author) && \Cryptography::compare($password, current($author)->get('password'), $isHash)) {
                self::$Author = current($author);

                // Only migrate hashes if there is no update available as the update might change the tbl_authors table.
                if (\Cryptography::requiresMigration(self::$Author->get('password'))) {
                    throw new ShellException('User details require updating. Please login to the admin interface.');
                }

                self::$Cookie->set('username', $username);
                self::$Cookie->set('pass', self::$Author->get('password'));
                self::Database()->update(array(
                    'last_seen' => \DateTimeObj::get('Y-m-d H:i:s'), ),
                    'tbl_authors',
                    sprintf(" `id` = %d", self::$Author->get('id'))
                );

                return true;
            }
        }

        return false;
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

    public static function error($string, $exit=true) {
        self::message("ERROR: $string", false, true, "red");
        if($exit == true) {
            exit(1);
        }
    }

    public static function warning($string) {
        self::message("WARNING: $string", false, true, "yellow");
    }
}
