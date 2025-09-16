<?php

require_once "logging.php";

if (file_exists('.env')) {
    $logger->info("Using configuration file .env");
    $Loader = (new josegonzalez\Dotenv\Loader('.env'))
            ->parse()
            ->prefix('DEMOINFO_')
            ->toEnv()
            ->putenv(true);
    
    $configuration = $Loader->toArray();
    $logger->debug("Out environment:");
    foreach ($configuration as $name => $value) {
        $logger->debug(" $name", [$value, getenv($name), getenv($name, true)]);
    }
} else {
    $logger->error("Configuration file missing .env");
    die("Not configured!");
}


