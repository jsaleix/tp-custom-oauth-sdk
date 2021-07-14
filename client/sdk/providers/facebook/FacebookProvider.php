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
        //print_r($_GET);
        $params = "client_id=".$this->client_id.
        "&redirect_uri=https://localhost/facebook/success
        &client_secret=".$this->client_secret."
        &code=".$_GET['code'];
        $url = "https://graph.facebook.com/v11.0/oauth/access_token?".$params;
        $fbResponse = file_get_contents('https://graph.facebook.com/v11.0/oauth/access_token?'.$params);
        $fbResponse = json_decode($fbResponse, true);
        if(!empty($fbResponse['access_token'])){
            $this->getInfos($fbResponse);
        }
        //print_r($_GET);
        //handle
    }

    public function handlePasswordType(): void{
        //handle
    }

    public function getInfos($token): array{
        print_r($token);
    }

    public function getLinks(): string{
        $params = "client_id=".$this->client_id."&redirect_uri=https://localhost/facebook/success&state=sqdsdsqdqsd";
        $html = '<h2>Login with Facebook</h2>';
        $html .= '<a href="https://www.facebook.com/v11.0/dialog/oauth?'.$params.'">login</a>';
        $html .= "<hr>";
        return $html;
    }

    public function getErrorMessage(): string{
        
    }

    public function handleRoute($route = null): ?array{
        switch($route){
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