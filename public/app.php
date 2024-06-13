<?php

/**
 * Creates a snippet
 *
 * @param array $context Contains configuration data which can be used to customize the snippet.
 *
 * @return string The HTML snippet.
 */
function createSnippet(array $context = [])
{
    $merchant_id = "";
    if ($context['fields']['merchant_id'] ?? '') {
        $merchant_id = $context['fields']['merchant_id']; //example field from payment settings
    }
    return
        '
        <script type="text/javascript" src="https://example.com/js/hello.js"></script>
        <script>window.plugin_hello_merchant_id = ' . json_encode($merchant_id) . ';</script>
        ';
}