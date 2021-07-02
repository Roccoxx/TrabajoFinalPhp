<?php

class BaseDeDatos
{
	private static $_instancia;

	private $_conexion;

	private function __construct()
	{
		$pdo = new PDO('mysql:host=127.0.0.1;dbname=sgu', 'root', '');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$this->_conexion = $pdo;
	}

	public static function getInstancia()
	{
		if ( self::$_instancia == null )
		{
			self::$_instancia = new BaseDeDatos();
		}

		return self::$_instancia;
	}

	public function getConexion()
	{
		return $this->_conexion;
	}
}