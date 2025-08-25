<?php
/*
  Copyright 2025 Tommi Rintala <tommi.rintala@vamk.fi>
 
  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at
  
     http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
 */

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

$stream = new StreamHandler('/tmp/demo_app_'.PHP_SAPI.'.log', Level::Debug);
$firephp = new FirePHPHandler();

$logger = new Logger('demoinfo');
$logger->pushHandler($stream);
$logger->pushHandler($firephp);


$security = new Logger('security');
$security->pushHandler($stream);
$security->pushHandler($firephp);

$logger->info('App starting', [ 'PHP_SAPI' => PHP_SAPI]);

$security->info('initialization completed.');
