<?php

require_once "lib/pages.php";
require_once "lib/htmlfuncs.php";


class Application {
    private ?string $_next = null;
    private int $_id;
    private string $_content;
    
    function __construct(?string $next = '') {
        global $pages;
        $flag = false;
        if ($next == '' or is_null($next)) {
            $flag = true;
            $this->_id = 0;
            $this->_next = $pages[1]['id'];
            $this->_content = $pages[0]['content'];
        } else {
            foreach ($pages as $id => $payload) {
                if ($flag) {
                    $this->_next = $payload['id'];
                    break;
                }
                if ($next == $payload['id']) {
                    $flag = true;
                    $this->_id = $id;
                    $this->_next = null;
                }               
            }
            if (is_null($this->_next)) {
                // Rollback to first item
                $this->_next = $pages[0]['id'];
            }
        }
    }
    public function dumpHtml(): void {
        $this->html_head();
        $this->html_body();
    }

    
    public function html_head(): void
    {
        print "<!DOCTYPE html>";
        print '<html lang="en">';
        print atag('head', [], [
            tag('meta', ['charset' => 'utf-8']),
            tag('meta', ['name' => 'viewport',
                         'content' => 'width=device-width, initial-scale=1']),
            tag('link', ['rel' => 'icon', 'type' => 'image/x-icon',
                         'href' => 'img/logo.ico']),
            comment('Do automatic refresh to next page'),
            tag('meta', ['http-equiv' => 'refresh',
                         'content' => sprintf("%d; url=%s?next=%s", 15,
                                              $_SERVER['REQUEST_URI'],
                                              $this->_next) ]),
            atag('title', [], ['DemoInfo']),
            comment('Bootstrap'),
            tag('link', ['href' => "https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css",
                         'rel'=>"stylesheet",
                         'integrity'=>"sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr",
                         'crossorigin'=>"anonymous"
            ]),
            atag('script', [
                'src'=>"https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js",
                'integrity'=>"sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q",
                'crossorigin'=>"anonymous"
            ]),
            // atag('script', ['src' => "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"], []),
            comment('Custom stylesheet'),
            atag('link', ['rel' => 'stylesheet',
                          'href' => 'css/style.css'],
                 []),           
            atag('script', ['src' => 'js/clock.js'], []),
                
        ]);
        /*
          <html lang="en">
          <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <meta name="csrf-token" content="{{ csrf_token() }}">
          <link rel="icon" type="image/x-icon" href="/img/anicare.ico">
          <title>DemoInfo</title>
          <!-- Bootstrap -->
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
          <!-- Fonts -->
          <link rel="preconnect" href="https://fonts.bunny.net">
          <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
          
          <?php
          ?>
          </head>
          <?php
        */
    }
    

    public function html_body(): void
    {
        global $pages;
        print atag('body', ['class' => 'text-center text-bg-light'], [
            atag('main', ['class' => 'px-3'], [
                atag('div', ['class' => 'container page-header'], [
                    atag('h1', ['class' => 'h1'], [
                        $pages[$this->_id]['title'] 
                    ]),
                    atag('div', ['class' => 'container', 'id' => 'clock'], []),
                    atag('p', ['class' => 'lead'], [
                        $pages[$this->_id]['content'] ]),
                    atag('div', ['class' => 'row'], [
                        atag('div', ['class' => 'col-2'], [ 'This page' ]),
                        atag('div', ['class' => 'col-4'], [ 'index='. $this->_id ]),
                        atag('div', ['class' => 'col-4'], [ $pages[$this->_id]['id'] ]),
                    ]),
                    atag('div', ['class' => 'row'], [
                        atag('div', ['class' => 'col-2'], [ 'Next page' ]),
                        atag('div', ['class' => 'col-4'], [ '' ]),
                        atag('div', ['class' => 'col-4'], [
                            atag('a', ['href' => '?next=' . $this->_next ], [ $this->_next ])
                        ]),
                    ]),
                ]),
            ]),

            /**
             * Page footer
             */
            atag('footer', ['class' => 'footer text-center bg-light'], [
                atag('div', ['class' => 'container'], [
                    atag('p', ['class' => 'text-muted'], [
                        '&copy; 2025 Tommi Rintala <em>tommi.rintala@vamk.fi</em>'
                    ])
                ])
            ]),
        ]);
        print( "</html>\n");
    }
}
