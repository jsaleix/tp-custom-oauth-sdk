<?php
namespace Sdk\Provider;

use Sdk\provider\ProviderInterface;

class GithubProvider extends Provider implements ProviderInterface
{
    public function __construct($client_id, $client_secret){
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    public function handleCodeType(){
        //handle
    }

    public function handlePasswordType(){
        //handle
    }

    public function getInfos($token){
        //infos
    }
}

?>