<?php
namespace Shell\Lib\interfaces;

interface Command
{
    public function run();
    public function usage();
}
