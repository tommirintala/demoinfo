<?php

require_once "vendor/autoload.php";
require_once "bootstrap/config.php";

require_once "lib/crawler.php";

define('OUTPATH', './tmp');

// Refactor this to fancy app ;-)


function get_page(string $url) {
    Spatie\Crawler\Crawler::create()
        ->setCrawlObservers([
            Spatie\Crawler\CrawlObservers\MyCrawlObserver::class
        ])
        ->setUserAgent('InfoTV')
        ->respectRobots()
        //->doNotExecuteJavaScript()
        //->setConcurrency(1)
        ->setTotalCrawlLimit(1)
        ->startCrawling($url);
}

$sources = [
    1 => 'https://thingspeak.mathworks.com/channels/349663'
];

if (!is_dir(OUTPATH)) {
    $logger->info("Creating new output temp directory", [OUTPATH]);
    mkdir(OUTPATH);
}

foreach ($sources as $index => $url) {
    $logger->info("Starting to crawl", [$url]);
    $res = get_page($url);
    $file = sprintf("%05d-%s.html", $index, date('omdHis'));
    $filename = join('/', [OUTPATH, $file]);
    $fout = fopen($filename, "w");
    fwrite($fout, $res);
    fclose($fout);
}

