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
    }

    private function createProvider(String $key, array $values): bool
    {
        if( file_exists(__DIR__ . "/Providers/". ucfirst(mb_strtolower($key)) . "/" . ucfirst(mb_strtolower($key)) ."Provider.php"))
        {
            include __DIR__ . "/Providers/".mb_strtolower($key)."/" . ucfirst(mb_strtolower($key)) ."Provider.php";
            $rawProviderName = ucfirst(mb_strtolower($key)) ;
            $providerName = 'Sdk\\Providers\\' . $rawProviderName . 'Provider';
            //$providerName = ucfirst(mb_strtolower($key)) . 'Provider';
            $provider = new $providerName(array_values($values)[0], array_values($values)[1]);
            self::$providers[$rawProviderName] = $provider;
            return true;
        }
        return false;
    }
    
    public static function getAllLinks()
    {
        foreach(self::$providers as $provider)
        {
            echo $provider->getLinks() . PHP_EOL;
        }
    }

    public function handleAuth()
    {
        $route = strtok($_SERVER['REQUEST_URI'], '?');
        $route = explode('/', $route);
        $route = array_slice($route, 1);

        if($route[0]){
            $typeAuth = ucfirst(mb_strtolower(str_replace('/', '', $route[0] )));
            $route = array_slice($route, 1);
            $route = implode('/', $route);
            if(array_key_exists($typeAuth, self::$providers)){
                switch($route){
                    case 'success':
                        return self::$providers[$typeAuth]->handleCodeType();
                    case 'error':
                        return self::$providers[$typeAuth]->getErrorMessage();
                    case 'token':
                        return self::$providers[$typeAuth]->getInfos();
                    case 'password':
                        return self::$providers[$typeAuth]->handlePasswordType();
                    default:
                        break;
                }
            }
        }
        self::getAllLinks();
    }


}

?>