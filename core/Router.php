<?php

class Router
{
	public static function route($url)
	{
		$controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';
		$method = $url[1] ?? 'index';
		$params = array_slice($url, 2);

		// Monter le chemin du controller
		$controllerPath = "../app/controllers/$controllerName.php";

		if (file_exists($controllerPath))
		{
			// Charge le fichier
			require_once $controllerPath;
			
			// instancie la méthode
			$controller = new $controllerName();

			if (method_exists($controller, $method))
			{
				call_user_func_array([$controller, $method], $params);
			}
			else 
			{
				echo "Méthode $method introuvable dans $controllerName";
			}
		}
		else
		{
			echo "Controller $controllerName introuvable";
		}
	}
}