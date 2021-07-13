<?php
namespace Sdk; 
use Sdk\Providers\ProviderInterface;
use Sdk\ConstantGetter;

class OauthSDK 
{
    private static array $providers;

    public function __construct(){//array $params
        $params = ConstantGetter::initialize();
        if(count($params) < 1){ throw new \Error('Missing parameters (at least one key in array)');}
        foreach($params as $key => $values)
        {
            //includeProvider($param);
            $provider = $this->createProvider($key, $values);
        }
        var_dump(self::$providers);

    }

    public function getAllLinks()
    {
        echo 'test';
    }

    private function createProvider(String $key, array $values): bool
    {
        if( file_exists(__DIR__ . "/Providers/". ucfirst(mb_strtolower($key)) . "/" . ucfirst(mb_strtolower($key)) ."Provider.php"))
        {
            include __DIR__ . "/Providers/".mb_strtolower($key)."/" . ucfirst(mb_strtolower($key)) ."Provider.php";
            $rawProviderName = ucfirst(mb_strtolower($key)) ;
            $providerName = 'Sdk\\Providers\\' . $rawProviderName . 'Provider';
            //$providerName = ucfirst(mb_strtolower($key)) . 'Provider';
            $provider = new $providerName(array_keys($values)[0], array_keys($values)[1]);
            self::$providers[$rawProviderName] = $provider;
            return true;
        }
        return false;
    }
    
}

?>