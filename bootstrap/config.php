<?php

if (file_exists('.env')) {
    $Loader = new josegonzalez\Dotenv\Loader('.env');
    $Loader->parse();
    $Loader->toEnv();
} else {
    die("Not configured!");
}


