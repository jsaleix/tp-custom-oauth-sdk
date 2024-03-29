<?php
namespace Sdk; 
use Sdk\Providers\ProviderInterface;
use Sdk\ConstantGetter;

class OauthSDK 
{
    private array $providers;

    public function __construct(){
        $params = ConstantGetter::initialize();
        if(!$params){ throw new \Error('No oauth2 provider set. Verify your env file.'); }
        if(count($params) < 1){ throw new \Error('Missing parameters (at least one key in array)');}
        foreach($params as $key => $values)
        {
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
            $provider = new $providerName(array_values($values)[0], array_values($values)[1]);
            $this->providers[$rawProviderName] = $provider;
            return true;
        }
        return false;
    }
    
    public function getAllLinks()
    {
        foreach($this->providers as $provider)
        {
            echo $provider->getLinks() . PHP_EOL;
        }
    }

    public function handleAuth() : ?array
    {
        if(count($this->providers) == 0){
            throw new Exception('No oauth2 provider set. Verify your env file.');
        }
        $route = strtok($_SERVER['REQUEST_URI'], '?');
        $route = explode('/', $route);
        $route = array_slice($route, 1);
        if($route[0]){
            $typeAuth = ucfirst(mb_strtolower(str_replace('/', '', $route[0] )));
            $route = array_slice($route, 1);
            $route = implode('/', $route);
            if(array_key_exists($typeAuth, $this->providers)){
                return $this->providers[$typeAuth]->handleRoute($route);
            }
        }
        self::getAllLinks();
        return null;
    }


}

?>