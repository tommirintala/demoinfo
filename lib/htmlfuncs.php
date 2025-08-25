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
