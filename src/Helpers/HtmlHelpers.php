<?php

/**
 * Create new HTML <a> component.
 *
 * @param string $url
 * @param string $content
 * @param array<string, string> $properties
 * @return string
 */
if (! function_exists('html_a_tag')) {
    function html_a_tag(
        string $url,
        string $content = 'Link',
        array $properties = []
    ): string
    {
        $tag = '<a ';
        foreach ($properties as $property => $value) {
            $tag .= $property . '="' . $value . '" ';
        }

        $tag .= 'href="' . $url . '">';
        $tag .= $content . '</a>';

        return $tag;
    }
}
