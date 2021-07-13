<?php
namespace Sdk; 

include(__DIR__ . "/ConstantMaker.php");

class OauthSDK 
{
    private ProviderInterface $providers;

    public function __construct(){//array $params
        new ConstantMaker();
        /*if(count($params) < 1){ throw new \Error('Missing parameters (at least one key in array)');}
        foreach($params as $param)
        {
            //includeProvider($param);
            $provider = $this->createProvider($param);
            if($provider){ $this->providers[] = $provider; }
        }*/
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