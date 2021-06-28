<?php
require 'sdk/index.php';

$sdk = new Sdk\OauthSDK([
    "facebook" => [
        "app_id" => "",
        "app_secret" => ""
    ],
    "oauth-server" => [
        "app_id" => "",
        "app_secret" => ""
    ],
]);

$sdk->getAllLinks();