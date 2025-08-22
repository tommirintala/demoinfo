<?php
require_once "lib/pages.php";

function comment($content): string
{
    return "<!-- $content -->\n";
}
function tag($name, $attrs = []): string
{
    $result = "<$name";
    foreach ($attrs as $id => $value) {
        $result .= " $id=\"$value\"";
    }
    $result .= ">\n";
    //foreach ($content as $block) {
    //    $result .= $block . "\n";
    //}
    //$result .= "</$name>\n";
    return $result;
}
function atag($name, $attrs = [], $content = []): string
{
    $result = "<$name";
    foreach ($attrs as $id => $value) {
        $result .= " $id=\"$value\"";
    }
    $result .= ">\n";
    foreach ($content as $block) {
        $result .= $block . "\n";
    }
    $result .= "</$name>\n";
    return $result;
}


class Application {
    private $_next = null;
    private $_id;
    private $_content;
    public function __construct($next = '') {
        global $pages;
        $flag = false;
        if ($next == '' or is_null($next)) {
            $flag = true;
            $this->_id = 0;
            $this->_next = $pages[0]['id'];
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
    public function dumpHtml() {
        $this->html_header();
        $this->html_body();
        $this->html_footer();
    }

    
    public function html_header()
    {
        print "<!DOCTYPE html>";
        print atag('html', ['lang' => 'en']);
        print atag('head', [], [
            tag('meta', ['charset' => 'utf-8']),
            tag('meta', ['name' => 'viewport',
                          'content' => 'width=device-width, initial-scale=1']),
            tag('link', ['rel' => 'icon', 'type' => 'image/x-icon',
                          'href' => 'img/logo.ico']),
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
            atag('link', ['rel' => 'stylesheet',
                          'href' => 'css/style.css'],
                 []),
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

    public function html_footer(): void
    {
        print atag('footer', ['class' => 'footer'], [
            atag('div', ['class' => 'container'], [
                atag('p', ['class' => 'text-muted'], [
                    '&copy; 2025 Tommi Rintala <em>tommi.rintala@vamk.fi</em>'
                ])
            ])
        ]);
    }

    public function html_body(): void
    {
        global $pages;
        print atag('body', ['class' => 'light-bg'], [
            atag('div', ['class' => 'container'], [
                atag('h1', ['class' => 'h1'], [
                    $pages[$this->_id]['title'] 
                ]),
                atag('p', [], [
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
        ]);
    }
}
