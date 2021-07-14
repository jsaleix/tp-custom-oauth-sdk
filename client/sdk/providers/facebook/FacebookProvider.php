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
        $params = "client_id=".$this->client_id.
        "&redirect_uri=https://localhost/facebook/success&client_secret=".$this->client_secret."&code=".$_GET['code'];
        $url = "https://graph.facebook.com/v11.0/oauth/access_token?".$params;

        $fbResponse = file_get_contents($url.$params);
        $fbResponse = json_decode($fbResponse, true);

        if(!empty($fbResponse['access_token'])){
            $this->getId($fbResponse['access_token']);
        }
    }
    
    public function getId($token): void{
        $url = "https://graph.facebook.com/me?fields=id&access_token=" . $token;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/json",
            "Authorization: Bearer ".$token,
            "user-agent: sdk"
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);
        curl_close($curl);
        $user = json_decode($result, true);
        if(!empty($user['id'])){
            $this->getInfos($user['id'], $token);
        }
    }

    public function getInfos($id, $token): array{
        $url = "https://graph.facebook.com/v11.0/$id";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Accept: application/json",
        "Authorization: Bearer ". $token,
        "user-agent: sdk"
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);
        curl_close($curl);
        $user = json_decode($result, true);
        var_dump($user);

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