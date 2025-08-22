<?php

function comment(string $content): string
{
    return "<!-- $content -->\n";
}
function tag(string $name, array $attrs = []): string
{
    $result = "<$name";
    foreach ($attrs as $id => $value) {
        $result .= " $id=\"$value\"";
    }
    $result .= ">\n";
    return $result;
}
function atag(string $name, array $attrs = [], array $content = []): string
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
