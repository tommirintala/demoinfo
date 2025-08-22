<?php

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

$stream = new StreamHandler('/tmp/demo_app.log', Level::Debug);
$firephp = new FirePHPHandler();

$logger = new Logger('demoinfo');
$logger->pushHandler($stream);
$logger->pushHandler($firephp);


$security = new Logger('security');
$security->pushHandler($stream);
$security->pushHandler($firephp);

$logger->info('App starting', [ 'PHP_SAPI' => PHP_SAPI]);

$security->info('initialization completed.');
