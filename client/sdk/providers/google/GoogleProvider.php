<?php
namespace Sdk\Providers;

use Sdk\Providers\ProviderInterface;
use Sdk\Providers\ProviderAbstract;

class GoogleProvider extends ProviderAbstract implements ProviderInterface
{
    public function __construct($client_id, $client_secret){
        $this->client_id     = $client_id;
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

    public function getLinks(): string{
        $state = 'dsdqsd';
        $link = "https://accounts.google.com/o/oauth2/v2/auth?";
        $link.= "scope=https%3A//www.googleapis.com/auth/drive.metadata.readonly&";
        $link.= "access_type=offline&";
        $link.= "include_granted_scopes=true&";
        $link.= "response_type=code&";
        $link.= "state=". $state ."&redirect_uri=". 'http://localhost:8082/google' ."&client_id=" . $this->client_id;
        $html = '<h2>Login with Google</h2>';
        $html .= '<a href='. $link .'>login</a>';
        $html .= "<hr>";
        return $html;
    }

    public function getErrorMessage(): string{
        
    }

    public function handleRoute($route = null): ?array{
        if(empty($_GET)){ header('location: '); }
        if(!empty($_GET['error'])){ 
            echo 'Une erreur est survenue';
            return null;;
        }
        [ 'code' => $code, 'state' => $state, 'scope' => $scope ] = $_GET;
        var_dump($_GET);
        return null;
    }
}

?>