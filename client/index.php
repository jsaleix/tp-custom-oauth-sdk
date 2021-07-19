<?php

require 'sdk/index.php';

$sdk = new Sdk\OauthSDK();
$data = $sdk->handleAuth();

if(!empty($data)){
    echo '<br><br>';
    echo '########## DATA ###########';
    echo '<br><br>';
    print_r($data);
}

