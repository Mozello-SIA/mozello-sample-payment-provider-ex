<?php

require 'libs/mozelloappsapi.php';
require 'libs/mozelloappshelpers.php';

require 'config.php';
require 'app.php';

$json_content = file_get_contents("php://input");
$json = json_decode($json_content, true);

$authCode = $json['authCode'] ?? null;
$hash = $_SERVER['HTTP_X_MOZELLO_HASH'];
$alias = $_SERVER['HTTP_X_MOZELLO_ALIAS'];

if (!AppsHelpers::isValidHashForString($json_content, $hash, $api_key)) {
    exit;
}

$api = new AppsAPI($api_key, $authCode);

$api->installed();

$default_data = [
    'fields' => $json['fields']
];
$api->setData($default_data);
$snippet = createSnippet($default_data);
$api->setSnippet($snippet);
