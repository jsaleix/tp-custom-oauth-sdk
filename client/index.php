<?php

require 'Autoload.php';

Autoload::register();

require 'sdk/index.php';

$sdk = new Sdk\OauthSDK();

$sdk->getAllLinks();