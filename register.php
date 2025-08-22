<?php

require_once "vendor/autoload.php";

use Ramsey\Uuid\Uuid;

$uuid = Uuid::uuid7();

printf("New app UUID = %s\n", $uuid->toString());

