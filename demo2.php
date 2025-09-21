<?php

header('Access-Control-Allow-Origin: *');
header('Integrity-Policy-Report-Only: blocked-destinations=(script), endpoints=(integrity-endpoint, some-other-integrity-endpoint)');

$url_list = [
    1 => "https://www.vamk.fi/",
    2 => "https://www.technobothnia.fi/",
    3 => "https://www.starelec.fi/",
    4 => "https://lukkarit.vamk.fi/",
    
        
        5 => "https://thingspeak.mathworks.com/channels/349663/",
];

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
if ($id < array_key_first($url_list)) {
    $id = array_key_first($url_list);
}

$url = $url_list[$id];

$next_id = $id+1;

if ($next_id > array_key_last($url_list)) {
    $next_id = array_key_first($url_list);
}




$content = file_get_contents("templates/demo2.html");

$content = str_replace("__FRAME_TARGET__", $url, $content);
$content = str_replace("__NEXT_PAGE_URL__", $hostpath, $content);

header('Refresh: 30 url=' . $hostpath);

echo $content;
