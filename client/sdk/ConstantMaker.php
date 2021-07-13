<?php

namespace Sdk;

class ConstantMaker
{

	private $envPath = __DIR__."/.env";
	private $data = [];

	public function __construct(){
		if(!file_exists($this->envPath)){
			die("Le fichier ".$this->envPath." n'existe pas");
		}

		//.env
		$this->parseEnv($this->envPath);

		if(!empty($this->data["ENV"])){
			// .env.prod ou .env.dev
			$this->parseEnv($this->envPath.".".$this->data["ENV"]);
		}
		//var_dump($this->data);
		$this->associateEnvVariables();
	}


	public function parseEnv($file){
		$handle = fopen($file, "r");
		if(!empty($handle)){
			while (!feof($handle)) {
				$line = trim(fgets($handle));
				//$line = DBHOST=database #ceci est un commentaire
				//$data["DBHOST"]="database";
				preg_match("/([^=]*)=([^#]*)/", $line, $results);
				if(!empty($results[1]) && !empty($results[2])){
					$this->data[$results[1]] = trim($results[2]);
				}
			}
		}
	}

	private function associateEnvVariables(){
		$associativeData = [];
		foreach($this->data as $key => $value){
			$OAuthName = explode("_",$key)[0];
			$associativeData[$OAuthName][$key] = $value;

		}
		foreach($associativeData as $key => $value){
			if(count($value) < 2){
				unset($associativeData[$key]);
			}
		}
		print_r($associativeData);
	}


}