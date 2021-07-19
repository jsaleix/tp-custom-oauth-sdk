<?php
namespace Sdk\Providers;

abstract class ProviderAbstract{
    protected string $client_id;
    protected string $client_secret;
    
    public function __construct($client_id, $client_secret){
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    protected function makeCurlGetRequest($url, $token = null ){
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/json",
            "user-agent: sdk"
        );

        if(!empty($token)){
            $headers[] = "Authorization: Bearer ".$token ;
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, true);
        return $result;
    }

}

?>