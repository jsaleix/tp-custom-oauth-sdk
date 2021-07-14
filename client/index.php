<?php

require 'sdk/index.php';

$cbData = function (array $data, string $type = null){
    var_dump($data);
};

$sdk = new Sdk\OauthSDK();
$data = $sdk->handleAuth($cbData);

if(!empty($data)){
    echo '<br><br>';
    echo '########## DATA ###########';
    var_dump($data);
}

