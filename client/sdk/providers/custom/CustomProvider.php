<?php
namespace Sdk\Providers;
/*require(dirname(__DIR__).'/ProviderAbstract.php');
require(dirname(__DIR__).'/ProviderInterface.php');*/

use Sdk\Providers\ProviderAbstract;
use Sdk\Providers\ProviderInterface;

class CustomProvider extends ProviderAbstract implements ProviderInterface
{

    public function handleCodeType():?array{
        ["code" => $code, "state" => $state] = $_GET;
        $result = file_get_contents("http://sdk-oauth-server:8081/token?"
            . "grant_type=authorization_code"
            . "&client_id=" . $this->client_id
            . "&client_secret=" . $this->client_secret
            . "&code=" . $code);
        $token = json_decode($result, true);

        if($token['access_token'])
        {
            return ($this->getInfos($token['access_token']));
        }else{
            echo 'Une erreur est survenue';
            return null;
        }
    }

    public function handlePasswordType():?array{
        if( isset($_POST['username']) && isset($_POST['password']))
        {
            [ 'username' => $username, 'password' => $pwd ] = $_POST;

            $result = file_get_contents("http://sdk-oauth-server:8081/token?"
                . "grant_type=password"
                . "&client_id=" . $this->client_id
                . "&client_secret=" . $this->client_secret
                . "&username=" . $username 
                . "&password=" . $pwd
            );
            $content = json_decode($result, true);
            
            if(isset($content["access_token"]))
            {
                return $this->getInfos($content["access_token"]);
            }else{
                echo 'Une erreur est survenue';
                return null;
            }
        }
    }

    public function getInfos($token):array{
        $context = stream_context_create([
            'http' => [
                'method' => "GET",
                'header' => "Authorization: Bearer " . $token
            ]
        ]);
        $result = file_get_contents("http://sdk-oauth-server:8081/api", false, $context);
        $user = json_decode($result, true);
        return $user;
    }

    public function getLinks(): string{
        $html = '<h2>Custom Oauth-server</h2>';
        $html .= '<p>Login with password</p><br>';
        $html .= "<form method='POST' action='/custom/password'>
                    <input type='text' name='username' placeholder='username'/>
                    <input type='password' name='password' placeholder='password'/>
                    <input type='submit' value='Login'/>
                <form><br>";
        $html .= '<h2>Login with Auth-code</h2><br>';
        $html .= "<a href='http://localhost:8081/auth?"
        . "response_type=code"
        . "&client_id=" . $this->client_id
        . "&scope=basic&state=dsdsfsfds'>Login</a><br>";
        $html .= "<hr>";
        return $html;
    }

    public function getErrorMessage(): void{
        echo 'Une erreur est survenue';
        return;
    }

    public function handleRoute($route = null): ?array{
        switch($route){
            case 'password':
                return $this->handlePasswordType();
            case 'success':
                return $this->handleCodeType();
            case 'error':
                return $this->getErrorMessage();
            case 'token':
                return $this->getInfos();
        }
        return null;
    }

}

?>