<?php

require_once "vendor/autoload.php";
require_once "bootstrap/config.php";

$logger->debug('Starting crawler');

// use Ramsey\Uuid\Uuid;

define('CACHE_DIR', './cache');

function initialize(): void {
    global $logger;
    if (!is_dir(CACHE_DIR)) {
        $logger->info("Creating missing cache dir", [CACHE_DIR]);
        mkdir(CACHE_DIR);
    }
}

function _get_id(string $str): string
{
    return md5($str);
}

function _get_filename(string $str): string {
    global $logger;
    $id = _get_id($str);
    $filename = CACHE_DIR . "/" . $id;
    $logger->debug(__METHOD__, [$str]);
    
    return $filename;
}

function is_stored(string $url): bool {
    global $logger;

    $fname = _get_filename($url);
    $have_it = file_exists($fname) ? true : false;
    $size = filesize($fname);
    if ( $size !== false && $size == 0) {
        $logger->warning("Deleted zero size file", [$url, $fname]);
        unlink($fname);
        return false;
    }
    $logger->debug(__METHOD__, [$url, $have_it]);
    return $have_it;
}

function store(string $url, string $content): string
{
    global $logger;
    $logger->debug(__METHOD__, [$url, 'len' => strlen($content)]);
    $filename = _get_filename($url);
    file_put_contents($filename, $content);
    return $filename;    
}

function get_cached_by_url(string $url): string {
    global $logger;
    $buffer = file_get_contents(_get_filename($url));
    $logger->debug(__METHOD__, [$url, strlen($buffer)]);
    return $buffer;
}

function get_cached_by_id(string $id): string {
    global $logger;
    // $logger->debug(__METHOD__, [$url]);
    $buffer = file_get_contents(CACHE_DIR . "/" . $id);
    $logger->debug(__METHOD__, [$id, strlen($buffer)]);
    return $buffer;
}

/*
function fetch_url(string $url): array
{
    $id = _get_filename($url);
    if (file_exists()) {
        $buffer = file_get_contents($get_filename($url));
    } else {
        $buffer = file_get_contents($url);
        file_put_contents(get_filename($url));
    }
    return [
        'id' => $id,
        'path' => CACHE_DIR . "/" . $id,
        'data' => $buffer,
    ];
}
*/

initialize();

$url = "https://www.vamk.fi";

if (is_stored($url)) {
    $file = get_cached_by_url($url);
    echo "Skip document, it's already handled\n";
    exit(0);
} else {
    $file = file_get_contents($url);
    store($url, $file);
}

/*
  if (!file_exists(CACHE_DIR . "/" . $id)) {
  $file = file_get_contents($url);
  file_put_contents(CACHE_DIR . "/" . $id, $file);    
  } else {
  $file = file_get_contents(CACHE_DIR . "/" . $id);
  }
*/

// Suppress warnings about invalid/malformed HTML
libxml_use_internal_errors(true);

$dom = new DOMDocument();
$dirty = false;

@$dom->loadHTML($file);

$images = $dom->getElementsByTagName('img');
$styles = $dom->getElementsByTagName('link');

$ss = $dom->getElementsByTagName('script');

echo "Got " . count($ss) . " script tags\n";
foreach ($ss as $i => $s) {
    //echo "s";
    $dirty = true;
    //print get_class($s);
    while ($q = $s->firstChild) {
        $q->remove();
    }
    //$p = $s->parentNode;
    //$p->removeChild($s);

    $s->remove();
}
echo "\n";

$ns = $dom->getElementsByTagName('noscript');
foreach ($ns as $n) {
    $n->remove();
}


$logger->info("Got some image nodes", [count($images)]);

foreach ($images as $img) {
    $src = $img->getAttribute('src');
    if (substr_compare($src, "data:", 0) == 1) {
        //echo "data: -link";
        // We have data: -link. Let's leave it like it is
    } else {
        $logger->debug("Fetching image", [$src]);
        $image = file_get_contents($src);
        $newname = store($src, $image);
        $img->setAttribute('src', $newname);
        $dirty = true;
        echo $src . PHP_EOL;
    }

}

if ($dirty) {
    $logger->info("Writing new version of HTML", [$url]);
    $bytes = $dom->saveHTMLFile(_get_filename($url));
}
