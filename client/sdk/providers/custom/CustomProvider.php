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
        $html = '<h2>Custom Oauth-server</h2>';
        $html .= '<p>Login with password</p><br>';
        $html .= "<form method='POST' action='/password'>
                    <input type='text' name='username' placeholder='username'/>
                    <input type='password' name='password' placeholder='password'/>
                    <input type='submit' value='Login'/>
                <form><br>";
        $html .= '<p>Login with Auth-code</p><br>';
        $html .= "<a href='http://localhost:8081/auth?"
        . "response_type=code"
        . "&client_id=" . $this->client_id
        . "&scope=basic&state=dsdsfsfds'>Login with oauth-server</a><br>";
        $html .= "<hr>";
        return $html;
    }

    public function getErrorMessage(): string{
        
    }

    public function handleRoute($route = null): ?array{
        return null;
    }

}

?>