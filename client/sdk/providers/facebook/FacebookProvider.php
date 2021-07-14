<?php
namespace Sdk\Providers;

use Sdk\Providers\ProviderInterface;
use Sdk\Providers\ProviderAbstract;


class FacebookProvider extends ProviderAbstract implements ProviderInterface
{
    public function handleCodeType(): ?array{
        $params = "client_id=".$this->client_id.
        "&redirect_uri=https://localhost/facebook/success&client_secret=".$this->client_secret."&code=".$_GET['code'];
        $url = "https://graph.facebook.com/v11.0/oauth/access_token?".$params;

        $fbResponse = file_get_contents($url.$params);
        $fbResponse = json_decode($fbResponse, true);

        if(!empty($fbResponse['access_token'])){
            return $this->getId($fbResponse['access_token']);
        }
    }
    
    public function getId($token): ?array{
        $url = "https://graph.facebook.com/me?fields=id&access_token=" . $token;
        $id = $this->makeCurlGetRequest($url);
        if($id == null){
            return null;
        }
        if(!empty($id['id'])){
            return $this->getInfos($id['id'], $token);
        }
    }

    public function getInfos($id, $token): array{
        $url = "https://graph.facebook.com/v11.0/$id";
        $user = $this->makeCurlGetRequest($url, $token);
        if($id == null){
            return null;
        }
        return $user;
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