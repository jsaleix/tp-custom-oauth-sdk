<?php

namespace Sdk;

class ConstantGetter
{
	private static $envPath = __DIR__."/.env";
	private static $data = [];

	public static function initialize(){
		if(!file_exists(self::$envPath))
		{
			die("Le fichier ". self::$envPath." n'existe pas");
		}

		self::parseEnv(self::$envPath);

		if(!empty(self::$data["ENV"])){
			self::parseEnv(self::$envPath.".".self::$data["ENV"]);
		}
		
		return self::associateEnvVariables();
	}


	public static function parseEnv($file){
		$handle = fopen($file, "r");
		if(!empty($handle)){
			while (!feof($handle)) {
				$line = trim(fgets($handle));
				preg_match("/([^=]*)=([^#]*)/", $line, $results);
				if(!empty($results[1]) && !empty($results[2])){
					self::$data[$results[1]] = trim($results[2]);
				}
			}
		}
	}

	private static function associateEnvVariables(){
		$associativeData = [];
		foreach(self::$data as $key => $value){
			$OAuthName = explode("_",$key)[0];
			$associativeData[$OAuthName][$key] = $value;

		}
		foreach($associativeData as $key => $value){
			if(count($value) < 2){
				unset($associativeData[$key]);
			}
		}
		return $associativeData;
	}


}