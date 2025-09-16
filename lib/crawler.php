<?php

namespace Spatie\Crawler\CrawlObservers;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class MyCrawlObserver extends CrawlObserver{
    public function willCrawl(UriInterface $url, ?string $linkText): void {
    }

    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null,
        ?string $linkText = null,): void
    {
        $logger->info("Crawled: $url");
    }

    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null,
        ?string $linkText = null,): void
    {
        $logger->error("Crawl failed: $url", [$requestException]);
    }

    public function finishedCrawling(): void
    {
        $logger->info("Crawling finished.");
    }
}
