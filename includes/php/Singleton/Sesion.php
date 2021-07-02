<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Registry/Registry.php';

class Sesion
{
	private static $_instancia;

	private function __construct()
	{
		session_start();

		if( !isset($_SESSION['initiated']) ){
            session_regenerate_id();
		    $_SESSION['initiated'] = true;
        }

		if ( isset($_SESSION['registry']) == false || $_SESSION['registry'] == null )
		{
			$_SESSION['registry'] = new Registry();
		}
	}

	public static function getInstancia()
	{
		if ( self::$_instancia == null )
		{
			self::$_instancia = new Sesion();
		}

		return self::$_instancia;
	}

	public function getRegistry()
	{
		return $_SESSION['registry'];
	}

	public function destruir()
	{
		session_unset();
		session_destroy();
	}
}