<?php

require_once "vendor/autoload.php";
require_once "bootstrap/config.php";

$version = system('git describe --tags --abbrev=0');
$url = getenv('REGISTER_URL', true) ?: getenv('REGISTER_URL');

print print_r($url, true);

if ( $url ) {
    echo "DEBUG: using url = $url";

    $inet = net_get_interfaces();
    print print_r($inet, true);
    $ip = [];
    $hw = "";
    if ($inet !== false) {
        foreach ($inet as $if => $d) {
            echo "DEBUG: interface = $if\n";
            foreach ($d as $m => $dd) {
                foreach ($dd as $idx => $data) {
                    if (isset($data['address']) && !in_array($data['address'], [
                        '::1', '127.0.0.1'])) {
                        $ip[] = $data['address'];
                    }                    
                }
            }
        }
    } else {
        $ip = ["n/a"];
    }

    $options = [
        CURLOPT_HEADER => 0,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => [
            'ip' => join(', ', $ip),
            'time' => time()
        ],
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_USERAGENT => "demoinfo/$version"
    ];

    print print_r($options, true);
    
    $ch = curl_init($url);

    curl_setopt_array($ch, $options);
    if (curl_exec($ch) === false) {
        echo "Register error: " . curl_error($ch) . "\n";
        die();
    }
} else {
    echo "No registration url defined!\n";
}
