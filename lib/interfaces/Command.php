<?php
namespace Shell\lib\interfaces;

interface Command
{
    public function run();
    public function usage();
}
