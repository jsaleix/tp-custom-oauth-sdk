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
        //print_r($_GET);
        $link = "client_id=".$this->client_id."&client_secret=".$this->client_secret."&code=".$_GET['code']."&redirect_uri=http://localhost:8082/github/token";
        echo $link;
        //handle
    }

    public function handlePasswordType(): void{
        //handle
    }

    public function getInfos($token): array{
        //infos
    }

    public function getLinks(): string{
        $link = "client_id=".$this->client_id."&redirect_uri=http://localhost:8082/github/success&state=sqdsdsqdqsd";
        $html = '<h2>Login with Github</h2>';
        $html .= '<a href="https://github.com/login/oauth/authorize?'.$link.'">login</a>';
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