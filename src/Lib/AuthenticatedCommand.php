<?php
namespace Symphony\Shell\Lib;

abstract class AuthenticatedCommand implements Interfaces\Command {
    abstract public function authenticate();
}
