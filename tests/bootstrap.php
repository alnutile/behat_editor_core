<?php

$autoloadFile = __DIR__ . '/../vendor/autoload.php';
require_once  __DIR__ . '/../vendor/teqneers/php-stream-wrapper-for-git/tests/TQ/Tests/Helper.php';
if (!file_exists($autoloadFile)) {
    throw new RuntimeException('Install dependencies to run test suite.');
}
require_once $autoloadFile;

$loader = new \Composer\Autoload\ClassLoader();
$loader->add('BehatAppTests\\Tests', 'tests/');
$loader->register();
