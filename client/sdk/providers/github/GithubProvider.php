<?php
namespace Sdk\Providers;

use Sdk\Providers\ProviderInterface;
use Sdk\Providers\ProviderAbstract;


class GithubProvider extends ProviderAbstract implements ProviderInterface
{

    public function handleCodeType(): void{
        $content = http_build_query(array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'code' => $_GET['code'],
            'redirect_uri' => 'https://localhost/github/token'
        ));
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header'=> "Content-type: application/x-www-form-urlencoded\r\n",
                'content'=> $content,
            )
        ));
        $githubResponse = file_get_contents('https://github.com/login/oauth/access_token', null, $context);
        $this->sanatizeTokenResponse($githubResponse);
        //print_r($githubResponse);
        $this->getInfos($githubResponse['access_token']);
        
    }

    public function handlePasswordType(): void{
        //handle
    }

    public function getInfos(string $token): array{
        $url = "https://api.github.com/user";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Accept: application/json",
        "Authorization: token ".$token,
        "user-agent: sdk"
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);
        curl_close($curl);
        $user = json_decode($result, true);
        var_dump($user);
        return $user;
    }

    public function getLinks(): string{
        $params = "client_id=".$this->client_id."&redirect_uri=https://localhost/github/success&state=sqdsdsqdqsd";
        $html = '<h2>Login with Github</h2>';
        $html .= '<a href="https://github.com/login/oauth/authorize?'.$params.'">login</a>';
        $html .= "<hr>";
        return $html;
    }

    public function getErrorMessage(): void{
        echo 'Une erreur est survenue';
    }

    private function sanatizeTokenResponse(string &$array): void{
        $array  = explode("&", $array );
        foreach($array as $key => $value){
            $cleanArray = explode("=", $value);
            $array[$cleanArray[0]] = $cleanArray[1];
            unset($array[$key]);
        }
    }
    
    public function handleRoute($route = null): ?array{
        if(!empty($_GET['error'])){ 
            $this->getErrorMessage();
        }
        switch($route){
            case 'success':
                return $this->handleCodeType();
            case 'error':
                return $this->getErrorMessage();
        }
        return null;
    }
}

?>