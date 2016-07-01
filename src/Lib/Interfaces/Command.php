<?php
namespace Symphony\Shell\Lib\Interfaces;

interface Command
{
    public function run();
    public function usage();
}
