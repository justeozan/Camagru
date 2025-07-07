<?php

class UserController {

	public function register()
	{
		require_once '../app/views/register.php';
	}

	public function registerSubmit()
	{
		// traitement du formulaire
	}

	public function login()
	{
		require_once '../app/views/login.php';
	}

	public function loginSubmit()
	{
		// traitement du formulaire
	}
}