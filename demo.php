<?php

require_once "bootstrap/config.php";

$uriproto = $_SERVER['SERVER_PROTOCOL'];
$urihost = $_SERVER['SERVER_NAME'];
$uripath = strtok($_SERVER['REQUEST_URI'], '?');


                     
header('Cache-Control: no-cache');




$pages = array(
    1 => 'https://www.vamk.fi/',
    2 => 'https://www.google.com/',
    3 => 'https://www.technobothnia.fi/',
);

$id = isset($_REQUEST['pageid']) ? $_REQUEST['pageid'] : -1;

if ($id == -1) {
    $logger->debug("The PAGEID is not set");
    $id = array_key_first($pages);
} else {
    $logger->debug("The PAGEID is", [$id]);
    reset($pages);
    while(true) {
        if ($id != key($pages)) {
            next($pages);
        } else {
            if ($id == array_key_last($pages)) {
                $id = array_key_first($pages);
            } else {
                next($pages);
                $id = key($pages);
            }
            break;
        }
    }

}

$myurl = sprintf("%s://%s/%s?pageid=%d", $uriproto, $urihost, $uripath, $id);

header('Refresh: 30; url=' . $myurl);

$logger->debug("demo.php: working with ID", [$id]);

if (array_key_exists($id, $pages)) {
    $logger->debug("Found in pagelist", [$pages[$id]]);
    $page = file_get_contents($pages[$id]);
} else {
    $logger->debug("Show the 'noshow' -template");
    $page = file_get_contents('templates/noshow.html');
    //$path = sprintf("%s?pageid=%d",
    //                ,
    //                $id);
    str_replace($page, "__REFRESH_PATH__", $myurl);
    $logger->debug("set refresh path to: $myurl");
    exit(0);
}


if (isset($_SERVER['REQUEST_URI'])) {
    $url = strtok($_SERVER['REQUEST_URI'], '?');
} else {
    $url = "/";
}

$logger->debug("Header stuff done");

libxml_use_internal_errors(true);

$dom = new DOMDocument();
$dom->loadHtml($page,
               LIBXML_NOERROR |
               LIBXML_NOWARNING
);

$logger->debug("DOM Load done");

$head = $dom->getElementsByTagName('head')->item(0);
if ($head) {
    $logger->debug("Found HEAD -tag");
    $refresh = $dom->createElement('meta');
    $refresh->setAttribute("http-equiv", "refresh");
    $refresh->setAttribute("content", "30; url=$url?pageid=".$id);    
    /*
    $refresh = new DOMElement("meta");
    */
    $head->appendChild($refresh);
    
} else {
    $logger->debug("No HEAD tag");
}

$scripts = $dom->getElementsByTagName('script');
foreach ($scripts as $s) {
    $s->remove();
}

echo $dom->saveHTML();

$logger->debug("Flushed the page");
