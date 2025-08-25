<?php

/**
 * Create HTML comment tag with content
 * @param string $content The content inside Comment tag
 */
function comment(string $content = ''): string
{
    return "<!-- $content -->\n";
}

/**
 * Create a html tag.
 *
 * @param string $name Tag name
 * @param array<string, string> $attrs Attribute, value pairs.
 * @return string The generated content.
 */
function tag(string $name = 'meta',
             array $attrs = []): string
{
    $result = "<$name";
    foreach ($attrs as $id => $value) {
        $result .= " $id=\"$value\"";
    }
    $result .= ">\n";
    return $result;
}

/**
 * Create a html tag with content
 *
 * @param string $name Tag name
 * @param array<string, string> $attrs Attribute, value pairs.
 * @param array<string> $content Array of content elements used.
 * @return string The generated html.
 */
function atag(string $name = 'div',
              array $attrs = [],
              array $content = []): string
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
