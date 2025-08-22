<?php

require_once "vendor/autoload.php";
require_once "bootstrap/logging.php";

require_once "lib/main.php";
require_once "lib/html-header.php";
require_once "lib/html-body.php";
require_once "lib/html-footer.php";


$app = new Application();
$app->dumpHtml();

