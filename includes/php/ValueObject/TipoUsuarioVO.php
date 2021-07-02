<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/ValueObject/ValueObject.php';

class TipoUsuarioVO extends ValueObject
{
	public $idTipoUsuario;
	public $nombre;
	public $descripcion;
}