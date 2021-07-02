<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/ActiveRecord/ActiveRecord.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/ValueObject/PersonaVO.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Singleton/BaseDeDatos.php';

class Persona extends ActiveRecord
{
	private $_vo;

	public function get()
	{
		return $this->_vo;
	}

	public function set(ValueObject $value)
	{
		$this->_vo = $value;
	}

	public function fetch($id)
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "select * from persona where idpersona = ?";

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();

		$vo = null;
		$resultado = $stmt->fetchObject();

		if ( $resultado != null )
		{
			$vo = new PersonaVO();
			$vo->idPersona = $resultado->idpersona;
			$vo->idTipoDocumento = $resultado->idtipodocumento;
			$vo->apellido = $resultado->apellido;
			$vo->nombre = $resultado->nombre;
			$vo->numeroDocumento = $resultado->numerodocumento;
			$vo->sexo = $resultado->sexo;
			$vo->nacionalidad = $resultado->nacionalidad;
			$vo->email = $resultado->email;
			$vo->telefono = $resultado->telefono;
			$vo->celular = $resultado->celular;
			$vo->provincia = $resultado->provincia;
			$vo->localidad = $resultado->localidad;
		}

		$this->_vo = $vo;
	}

	public function insert()
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "insert into persona (idtipodocumento,apellido,nombre,numerodocumento,sexo,nacionalidad,email,telefono,celular,provincia,localidad)
			values (:idTipoDocumento,
			:apellido,
			:nombre,
			:documento,
			:sexo,
			:nacionalidad,
			:email,
			:telefono,
			:celular,
			:provincia,
			:localidad)";

        $stmt = $pdo->prepare($query);

        $stmt->bindValue(':idTipoDocumento', $this->_vo->idTipoDocumento,PDO::PARAM_INT);
        $stmt->bindValue(':apellido', ($this->_vo->apellido),PDO::PARAM_STR);
        $stmt->bindValue(':nombre', ($this->_vo->nombre),PDO::PARAM_STR);
        $stmt->bindValue(':documento', $this->_vo->numeroDocumento,PDO::PARAM_INT);
        $stmt->bindValue(':sexo', ($this->_vo->sexo),PDO::PARAM_STR);
        $stmt->bindValue(':nacionalidad', ($this->_vo->nacionalidad),PDO::PARAM_STR);
        $stmt->bindValue(':email', ($this->_vo->email),PDO::PARAM_STR);
        $stmt->bindValue(':telefono', ($this->_vo->telefono),PDO::PARAM_STR);
        $stmt->bindValue(':celular', ($this->_vo->celular),PDO::PARAM_STR);
        $stmt->bindValue(':provincia', ($this->_vo->provincia),PDO::PARAM_STR);
        $stmt->bindValue(':localidad', ($this->_vo->localidad),PDO::PARAM_STR);

        $stmt->execute();

		$this->_vo->idPersona = $pdo->lastInsertId();
	}

	public function update()
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "update persona set
				idtipodocumento = :idTipoDocumento,
				apellido = :apellido,
				nombre = :nombre,
				numerodocumento = :documento,
				sexo = :sexo,
				nacionalidad = :nacionalidad,
				email = :email,
				telefono = :telefono,
				celular = :celular,
				provincia = :provincia,
				localidad = :localidad
			where
				idpersona = :idPersona";

        $stmt = $pdo->prepare($query);

        $stmt->bindValue(':idTipoDocumento', $this->_vo->idTipoDocumento,PDO::PARAM_INT);
        $stmt->bindValue(':apellido', ($this->_vo->apellido),PDO::PARAM_STR);
        $stmt->bindValue(':nombre', ($this->_vo->nombre),PDO::PARAM_STR);
        $stmt->bindValue(':documento', $this->_vo->numeroDocumento,PDO::PARAM_INT);
        $stmt->bindValue(':sexo', ($this->_vo->sexo),PDO::PARAM_STR);
        $stmt->bindValue(':nacionalidad', ($this->_vo->nacionalidad),PDO::PARAM_STR);
        $stmt->bindValue(':email', ($this->_vo->email),PDO::PARAM_STR);
        $stmt->bindValue(':telefono', ($this->_vo->telefono),PDO::PARAM_STR);
        $stmt->bindValue(':celular', ($this->_vo->celular),PDO::PARAM_STR);
        $stmt->bindValue(':provincia', ($this->_vo->provincia),PDO::PARAM_STR);
        $stmt->bindValue(':localidad', ($this->_vo->localidad),PDO::PARAM_STR);
        $stmt->bindValue(':idPersona', $this->_vo->idPersona,PDO::PARAM_INT);

        $stmt->execute();
	}

	public function delete($id)
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "delete from persona where idpersona = ?";

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
	}

	private function _validarTelefono($valor)
	{
		$telefono = explode('-', $valor);

		if ( count($telefono) != 2 )
		{
			return false;
		}

		if ( is_numeric($telefono[0]) == false || is_numeric($telefono[1]) == false)
		{
			return false;
		}

		if ( ( strlen($telefono[0]) + strlen($telefono[1]) ) < 10 )
		{
			return false;
		}

		return true;
	}

	private function _validarEmail($valor)
	{
		$email = explode('@', $valor);

		return ( count($email) == 2 );
	}

	public function validarContactos()
	{
		$telefono = ($this->_vo->telefono != null) ? $this->_validarTelefono($this->_vo->telefono) : true;
		$celular = ($this->_vo->celular != null) ? $this->_validarTelefono($this->_vo->celular) : true;
		$email = ($this->_vo->email != null) ? $this->_validarEmail($this->_vo->email) : true;

		return $telefono && $celular && $email;
	}
}