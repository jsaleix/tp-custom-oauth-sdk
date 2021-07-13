<?php
namespace Sdk\Providers;
/*require(dirname(__DIR__).'/ProviderAbstract.php');
require(dirname(__DIR__).'/ProviderInterface.php');*/

use Sdk\Providers\ProviderAbstract;
use Sdk\Providers\ProviderInterface;

class CustomProvider extends ProviderAbstract implements ProviderInterface
{
    public function __construct($client_id, $client_secret){
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    public function handleCodeType():void{
        //handle
    }

    public function handlePasswordType():void{
        //handle
    }

    public function getInfos($token):array{
        //infos
    }

    public function getLinks(): string{
        return '<a href="/custom">Login</a>';
    }
}

?>