<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/ValueObject/ValueObject.php';

class UsuarioVO extends ValueObject
{
	public $idUsuario;
	public $idPersona;
	public $idTipoUsuario;
	public $nombre;
	public $contrasenia;
}