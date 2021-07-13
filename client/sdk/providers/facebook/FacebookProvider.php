<?php
namespace Sdk\Providers;

use Sdk\Providers\ProviderInterface;
use Sdk\Providers\ProviderAbstract;


class FacebookProvider extends ProviderAbstract implements ProviderInterface
{
    public function __construct($client_id, $client_secret){
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    public function handleCodeType(): void{
        //handle
    }

    public function handlePasswordType(): void{
        //handle
    }

    public function getInfos($token): array{
        //infos
    }
}

?>