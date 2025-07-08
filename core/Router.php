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
			
			// Vérifier que la classe existe
			if (class_exists($controllerName))
			{
				// Instancier le contrôleur
				$controller = new $controllerName();

				if (method_exists($controller, $method))
				{
					call_user_func_array([$controller, $method], $params);
				}
				else 
				{
					// Méthode non trouvée - utiliser ErrorController
					self::handleMethodNotFound($controllerName, $method);
				}
			}
			else
			{
				// Classe non trouvée - utiliser ErrorController
				self::handleControllerNotFound($controllerName);
			}
		}
		else
		{
			// Fichier contrôleur non trouvé - utiliser ErrorController
			self::handleControllerNotFound($controllerName);
		}
	}

	// Gérer l'erreur de contrôleur non trouvé
	private static function handleControllerNotFound($controllerName)
	{
		require_once "../app/controllers/ErrorController.php";
		$errorController = new ErrorController();
		$errorController->controllerNotFound($controllerName);
	}

	// Gérer l'erreur de méthode non trouvée
	private static function handleMethodNotFound($controllerName, $method)
	{
		require_once "../app/controllers/ErrorController.php";
		$errorController = new ErrorController();
		$errorController->methodNotFound($controllerName, $method);
	}

	// Méthode statique pour déclencher une 404 depuis n'importe où
	public static function notFound($message = null)
	{
		require_once "../app/controllers/ErrorController.php";
		$errorController = new ErrorController();
		$errorController->notFound($message);
	}
}