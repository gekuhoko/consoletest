<?php

namespace App;

$path = __DIR__.'/vendor/autoload.php';
require $path;

use Console\Console\Command\GreetCommand;
use Console\Console\Command\WeatherCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new WeatherCommand());
$application->run();