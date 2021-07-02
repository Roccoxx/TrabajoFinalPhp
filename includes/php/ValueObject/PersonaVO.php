<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/ValueObject/ValueObject.php';

class PersonaVO extends ValueObject
{
	public $idPersona;
	public $idTipoDocumento;
	public $apellido;
	public $nombre;
	public $numeroDocumento;
	public $sexo;
	public $nacionalidad;
	public $email;
	public $telefono;
	public $celular;
	public $provincia;
	public $localidad;
}