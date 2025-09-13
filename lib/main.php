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


require_once "lib/pages.php";
require_once "lib/htmlfuncs.php";

define('REFRESH_SECONDS', 30);

class Application {
    private ?string $_next = null;
    private int $_id;
    private string $_content;

    /**
     * Default constructor
     *
     * @param ?string $next The sub module page the app was called for
     */
    function __construct(?string $next) {
        global $pages;
        $flag = false;
        if (is_null($next) or $next == '') {
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

    /**
     * Dump HTML output
     */
    public function dumpHtml(): void {
        $this->html_head();
        $this->html_body();
    }

    /**
     * Generate and output to STDOUT the HTML header, with server 'header' parts
     */
    private function html_head(): void
    {
        header('Cache-Control: no-cache');
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = strtok($_SERVER['REQUEST_URI'], '?');
        } else {
            $url = "/";
        }
        
        print "<!DOCTYPE html>\n";
        print '<html lang="en">';
        print atag('head', [], [
            tag('meta', ['charset' => 'utf-8']),
            tag('meta', ['name' => 'description',
                         'content' => 'InfoTV Example']),
            tag('meta', ['name' => 'keywords',
                         'content' => 'HTML, InfoTV, Example']),
            tag('meta', ['name' => 'author',
                         'content' => 'Tommi Rintala']),
            tag('meta', ['name' => 'viewport',
                         'content' => 'width=device-width, initial-scale=1']),
            tag('link', ['rel' => 'icon', 'type' => 'image/x-icon',
                         'href' => 'img/favicon.ico']),
            comment('Do automatic refresh to next page'),
            tag('meta', ['http-equiv' => 'refresh',
                         'content' => sprintf("%d; url=%s?next=%s", REFRESH_SECONDS,
                                              $url,
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
    }

    /**
     * Locate the sub module (PHP) file, which is responsible for generating the output. 
     * @return string Evaluated output from sub module.
     */
    private function findAppFile(): string
    {
        global $pages;
        if ($this->_id) {
            if (file_exists($pages[$this->_id]['app'])) {
                ob_start();
                eval('?>' . file_get_contents($pages[$this->_id]['app']));
                $result = ob_get_clean();
                return $result;
            } else {
                return $this->_content;
            }
        }
        return '';
    }

    /**
     * Generate and output the HTML of actual page.
     */
    private function html_body(): void
    {
        global $pages;
        $file = $this->findAppFile();
        
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
                atag('div', ['class' => 'alert alert-primary'], [
                    $file
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
