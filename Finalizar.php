<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Factory/ActiveRecordFactory.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Singleton/Sesion.php';

$oSesion = Sesion::getInstancia();

$oRegistry = $oSesion->getRegistry();

if ($oRegistry->get('HUA') != md5($_SERVER['HTTP_USER_AGENT'])){
    die("Usted cambio de navegador!!!!!");
}

$pdo = BaseDeDatos::getInstancia()->getConexion();

$oPersonaVO = ( $oRegistry->exists('persona') == false ) ? new PersonaVO() : $oRegistry->get('persona');
$oUsuarioVO = ( $oRegistry->exists('usuario') == false ) ? new UsuarioVO() : $oRegistry->get('usuario');

$pdo->beginTransaction();

try
{
	$oPersona = ActiveRecordFactory::getPersona();
	$oUsuario = ActiveRecordFactory::getUsuario();

	$oPersona->set($oPersonaVO);
	$oUsuario->set($oUsuarioVO);

	$oPersona->insert();

	$oUsuario->get()->idPersona = $oPersona->get()->idPersona;

	$oUsuario->insert();

	$pdo->commit();

	$oSesion->destruir();
	header('location: Paso1.php');
}
catch (Exception $e)
{
	$pdo->rollBack();
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="ISO-8859-1">
	<title>SGU | Formulario de Inscripc&oacute;n</title>
	<link type="text/css" rel="stylesheet" href="/tp6/includes/css/estilos.css">
</head>
<body>

<div class="wraper">

	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/header.php'; ?>
	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/menu.php'; ?>

	<div class="mensaje">
		<h3>Error al registrar el usuario</h3>
		<p>
			Ha ocurrido un error al intentar registrar el usuario. Por favor intentelo nuevamente.
		</p>
		<div class="buttons">
			<input type="button" value="Anterior" onclick="document.location='Paso3.php'">
		</div>
	</div>

	<div class="push"></div>

</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/footer.php'; ?>
</body>
</html>