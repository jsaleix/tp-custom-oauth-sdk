<?php
namespace Sdk\Providers;

use Sdk\Providers\ProviderInterface;
use Sdk\Providers\ProviderAbstract;


class GithubProvider extends ProviderAbstract implements ProviderInterface
{
    public function __construct($client_id, $client_secret){
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    public function handleCodeType(): void{
        $content = http_build_query(array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'code' => $_GET['code'],
            'redirect_uri' => 'http://localhost:8082/github/token'
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
        print_r($githubResponse);
        
    }

    public function handlePasswordType(): void{
        //handle
    }

    public function getInfos($token): array{
        echo "INNFOOOO";
        //infos
    }

    public function getLinks(): string{
        $params = "client_id=".$this->client_id."&redirect_uri=http://localhost:8082/github/success&state=sqdsdsqdqsd";
        $html = '<h2>Login with Github</h2>';
        $html .= '<a href="https://github.com/login/oauth/authorize?'.$params.'">login</a>';
        $html .= "<hr>";
        return $html;
    }

    public function getErrorMessage(): string{
        
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
        switch($route){
            case 'success':
                return $this->handleCodeType();
            case 'error':
                return $this->getErrorMessage();
            case '':
                return $this->getInfos();
        }
        return null;
    }
}

?>