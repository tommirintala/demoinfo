<?php


require_once "vendor/autoload.php";
require_once "bootstrap/logging.php";

require_once "lib/main.php";



$next = isset($_GET['next']) ? $_GET['next'] : (isset($_POST['next']) ? $_POST['next'] : '');

$app = new Application($next);
$app->dumpHtml();

