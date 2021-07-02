<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/ActiveRecord/ActiveRecord.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/ValueObject/TipoUsuarioVO.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Singleton/BaseDeDatos.php';

class TipoUsuario extends ActiveRecord
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

		$query = "select * from tipousuario where idtipousuario = ?";

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();

		$vo = null;
		$resultado = $stmt->fetchObject();

		if ( $resultado != null )
		{
			$vo = new TipoUsuarioVO();
			$vo->idTipoUsuario = $resultado->idtipousuario;
			$vo->nombre = $resultado->nombre;
			$vo->descripcion = $resultado->descripcion;
		}

		$this->_vo = $vo;
	}

	public function insert()
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "insert into tipousuario (nombre,descripcion) values ('?','?')";

        $stmt = $pdo->prepare($query);

        $stmt->bindValue(1, ($this->_vo->nombre), PDO::PARAM_STR);
        $stmt->bindValue(2, ($this->_vo->descripcion), PDO::PARAM_STR);

        $stmt->execute();

		$this->_vo->idTipoUsuario = $pdo->lastInsertId();
	}

	public function update()
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "update tipousuario set nombre = '?', descripcion = '?' where idtipousuario = ?";

        $stmt = $pdo->prepare($query);

        $stmt->bindValue(1, ($this->_vo->nombre), PDO::PARAM_STR);
        $stmt->bindValue(2, ($this->_vo->descripcion), PDO::PARAM_STR);
        $stmt->bindValue(3, $this->_vo->idTipoUsuario, PDO::PARAM_INT);

        $stmt->execute();
	}

	public function delete($id)
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "delete from tipousuario where idtipousuario = ?";

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
	}

	public function fetchAll()
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$query = "select * from tipousuario";

		$stmt = $pdo->query($query);

		$resultados = array();

		while ( ( $resultado = $stmt->fetchObject() ) != false )
		{
			$vo = new TipoUsuarioVO();
			$vo->idTipoUsuario = $resultado->idtipousuario;
			$vo->nombre = $resultado->nombre;
			$vo->descripcion = $resultado->descripcion;

			$resultados[] = $vo;
		}

		return $resultados;
	}
}