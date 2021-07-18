<?php

namespace Sdk;

class Autoload
{

	public static function register(){

		spl_autoload_register(function($class){
			//  \Core\Router - >  Core\Router
			$class = ltrim($class, "\\");

			// Core\Router -> Core/Router
			$class = str_replace("\\", "/", $class);

			if( file_exists(dirname(__DIR__) . '/' . $class.".php")){
				include dirname(__DIR__) . '/' . $class.".php";
			}
			
		});

	}


}