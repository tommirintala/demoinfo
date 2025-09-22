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


require_once "vendor/autoload.php";
require_once "bootstrap/config.php";

// require_once "lib/main.php";

header('Cache-control: No-Cache');
header('P3P: CP="ALL DSP NID CURa ADMa DEVa HISa OTPa OUR NOR NAV DEM"');
// header('Access-Control-Allow-Origin: *');
//header('Integrity-Policy-Report-Only: blocked-destinations=(script), endpoints=(integrity-endpoint, some-other-integrity-endpoint)');
//header('Content-Security-Policy: default-src https: http:');

if (! defined('DEMOINFO_REFRESH_TIME')) {
    define('DEMOINFO_REFRESH_TIME', 30);
}

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : -1;

/**
 * @param string $id
 * @return array<int, int>
 */
function get_next_page(string $id): array {
    $url_list = [        
        1 => build_host() . '/demoinfo/apps/demo1.php',
        2 => build_host() . '/demoinfo/apps/demo2.php',
        3 => 'https://thingspeak.mathworks.com/channels/349663',
    ];
    if ($id < array_key_first($url_list)) {
        $id = array_key_first($url_list);
    }
    
    $url = $url_list[$id];
    
    $next_id = $id+1;
    
    if ($next_id > array_key_last($url_list)) {
        $next_id = array_key_first($url_list);
    }
    return array($id, $next_id, $url_list[$id]);
}

function build_host(): string
{
    return "http" . (isset($_SERVER['HTTPS']) ? "s" : "") .
        "://" . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'] ) ;
}

/**
 * Build query URL for next page
 */
function build_url(int $next_id): string
{
    $hostpath = build_host() .
        strtok($_SERVER['REQUEST_URI'], "?") . "?id=" . $next_id;
    
    return $hostpath;
}

list($id, $next_id, $url) = get_next_page($id);

header('Refresh: ' . DEMOINFO_REFRESH_TIME . " url=" . build_url($next_id));

//$app = new Application($next);
//$app->dumpHtml();

$content = file_get_contents("templates/main.html");
$content = str_replace("__FRAME_TARGET__", $url, $content); // uild_url($next_id), $content);
$content = str_replace("__NEXT_PAGE_URL__", build_url($next_id), $content);
echo $content;
