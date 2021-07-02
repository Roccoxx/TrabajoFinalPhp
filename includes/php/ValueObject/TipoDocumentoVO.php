<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/ValueObject/ValueObject.php';

class TipoDocumentoVO extends ValueObject
{
	public $idTipoDocumento;
	public $nombre;
	public $descripcion;
}