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

    public function handleCodeType($data): void{
        [ 'code' => $code, 'state' => $state, 'scope' => $scope ] = $data;

        $data = 'code=' . $code.
            '&client_id='.  $this->client_id.
            '&client_secret=' . $this->client_secret.
            '&redirect_uri='.('https://localhost/google').
            '&grant_type=authorization_code';

        $curl = curl_init();
        curl_setopt_array($curl, array(
                   CURLOPT_URL => "https://oauth2.googleapis.com/token",
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_ENCODING => "",
                   CURLOPT_MAXREDIRS => 10,
                   CURLOPT_TIMEOUT => 30,
                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                   CURLOPT_CUSTOMREQUEST => "POST",
                   CURLOPT_POSTFIELDS => $data,
                   CURLOPT_HTTPHEADER => array(
                       "cache-control: no-cache",
                       "content-type: application/x-www-form-urlencoded"
                   ),
               ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
       
        curl_close($curl);
        echo  '<br>';

        $res = json_decode($response, true);
        if(!empty($res['access_token'])){
            $this->getInfos($res);
        }

    }

    public function getInfos($data): ?array{
        $context = stream_context_create([
            'http' => [
                'method' => "GET",
                'header' => "Authorization: Bearer " . $data['access_token']
            ]
        ]);
        $result = file_get_contents("https://www.googleapis.com/oauth2/v1/userinfo?alt=json", false, $context);
        $user = json_decode($result, true);
        var_dump($user);  
        return null;
    }

    public function getLinks(): string{
        $state = 'dsdqsd';
        
        $link = "https://accounts.google.com/o/oauth2/v2/auth?";
        $link.= "scope=https://www.googleapis.com/auth/userinfo.profile";
        $link.= "&access_type=offline";
        $link.= "&include_granted_scopes=true";
        $link.= "&response_type=code";
        $link.= "&state=". $state ."&redirect_uri=" . urlencode('https://localhost/google') . "&client_id=" . $this->client_id;
        
        $html = '<h2>Login with Google</h2>';
        $html .= '<a href='. $link .'>login</a>';
        $html .= "<hr>";
        return $html;
    }

    public function getErrorMessage(): string{
        echo 'Une erreur est survenue';
        return null;
    }

    public function handleRoute($route = null): ?array{
        $uri = explode('#', $_SERVER['REQUEST_URI']);
        if(empty($_GET)){ 
            header('location: /'); 
            exit;
        }

        if(!empty($_GET['error'])){ 
            $this->getErrorMessage();
        }

        $this->handleCodeType($_GET);
        return null;
    }
}

?>