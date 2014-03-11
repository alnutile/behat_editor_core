<?php namespace BehatApp;

use TQ\Git\StreamWrapper\StreamWrapper;
use TQ\Git\Cli\Binary;
use TQ\Git\Repository\Repository;


class GitHelper extends Repository {

    const GIT_BINARY = '/usr/bin/git';

    public $git_wrapper;

}