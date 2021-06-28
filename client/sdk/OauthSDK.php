<?php
namespace Sdk; 

class OauthSDK 
{
    private ProviderInterface $providers;

    public function __construct(array $params){
        if(count($params) < 1){ throw new \Error('Missing parameters (at least one key in array)');}
        foreach($params as $param)
        {
            //includeProvider($param);
            $provider = $this->createProvider($param);
            if($provider){ $this->provider[] = $provider; }
        }
    }

    public function getAllLinks()
    {
        echo 'test';
    }

    private function createProvider(array $params): ?ProviderInterface 
    {
        /*if( file_exists("./providers/".$param.".php"))
        {
            
        }*/
        return null;
    }
    
}

?>