<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Factory/ActiveRecordFactory.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Singleton/Sesion.php';

$oRegistry = Sesion::getInstancia()->getRegistry();

if ($oRegistry->get('HUA') != md5($_SERVER['HTTP_USER_AGENT'])){
    die("Usted cambio de navegador!!!!!");
}

if (!$oRegistry->exists('token') || !isset($_POST['token']) || $_POST['token'] != $oRegistry->get('token')){
    echo "Su token de sesion ha vencido";
    exit;
}

$oPersona = ActiveRecordFactory::getPersona();
$oUsuario = ActiveRecordFactory::getUsuario();

$aProvincias = array('Entre Rios', 'Sante Fe', 'Cordoba', 'Buenos Aires');

$validarTipoUsuario = false;
$validarNombreUsuario = false;
$validarContrasenia = false;

$validarApellido = false;
$validarNombre = false;
$validarTipoDocumento = false;
$validarNumeroDocumento = false;
$validarSexo = false;
$validarNacionalidad = false;
$validarContacto = false;
$validarProvincia = false;
$validarLocalidad = false;

if ( isset($_POST['bt_guardar']) == true )
{
    if(!ctype_digit($_POST['idUsuario'])) return;

	$oUsuario->fetch($_POST['idUsuario']);
	$oPersona->fetch($oUsuario->get()->idPersona);

	$oUsuarioVO = $oUsuario->get();
	$oPersonaVO = $oPersona->get();

	$oUsuarioVO->idTipoUsuario = ( isset($_POST['tipo_usuario']) == true ) ? htmlentities($_POST['tipo_usuario']) : 2;
	$oUsuarioVO->nombre = ( isset($_POST['nombre_usuario']) == true ) ? htmlentities($_POST['nombre_usuario']) : '';
	$oUsuarioVO->contrasenia = ( isset($_POST['contrasenia']) == true ) ? htmlentities($_POST['contrasenia']) : '';

	$oPersonaVO->apellido = ( isset($_POST['apellido']) == true ) ? htmlentities($_POST['apellido']) : '';
	$oPersonaVO->nombre = ( isset($_POST['nombre']) == true ) ? htmlentities($_POST['nombre']) : '';
	$oPersonaVO->idTipoDocumento = ( isset($_POST['tipo_documento']) == true ) ? htmlentities($_POST['tipo_documento']) : '';
	$oPersonaVO->numeroDocumento = ( isset($_POST['numero_documento']) == true ) ? htmlentities($_POST['numero_documento']) : '';
	$oPersonaVO->sexo = ( isset($_POST['sexo']) == true ) ? htmlentities($_POST['sexo']) : '';
	$oPersonaVO->nacionalidad = ( isset($_POST['nacionalidad']) == true ) ? htmlentities($_POST['nacionalidad']) : '';
	$oPersonaVO->email = ( isset($_POST['email']) == true ) ? htmlentities($_POST['email']) : '';
	$oPersonaVO->telefono = ( isset($_POST['telefono']) == true ) ? htmlentities($_POST['telefono']) : '';
	$oPersonaVO->celular = ( isset($_POST['celular']) == true ) ? htmlentities($_POST['celular']) : '';
	$oPersonaVO->provincia = ( isset($_POST['provincia']) == true ) ? htmlentities($_POST['provincia']) : '';
	$oPersonaVO->localidad = ( isset($_POST['localidad']) == true ) ? htmlentities($_POST['localidad']) : '';

	$oUsuario->set($oUsuarioVO);
	$oPersona->set($oPersonaVO);

	if ( ctype_digit($oUsuarioVO->idTipoUsuario) )
	{
        $validarTipoUsuario = true;
    }

    if ( ctype_alnum($oUsuarioVO->nombre) ){
        $validarNombreUsuario = true;
    }

	if ( $oUsuario->validarContrasenia() == true )
	{
		$validarContrasenia = true;
	}

	if( ctype_alpha($oPersonaVO->apellido) ){
        $validarApellido = true;
    }

    if( ctype_alpha($oPersonaVO->nombre) ){
        $validarNombre = true;
    }

	foreach ( ActiveRecordFactory::getTipoDocumento()->fetchAll() as $oTipoDocumento )
	{
		if ( $oPersonaVO->idTipoDocumento == $oTipoDocumento->idTipoDocumento )
		{
			$validarTipoDocumento = true;
		}
	}

    if ( ctype_digit($oPersonaVO->numeroDocumento) ){
        $validarNumeroDocumento = true;
    }

	$sexos = array('M','F');
	if ( in_array($oPersonaVO->sexo, $sexos) == true )
	{
		$validarSexo = true;
	}

	if( ctype_alpha($oPersonaVO->nacionalidad) ){
	    $validarNacionalidad = true;
    }

    if ( $oPersona->validarContactos() == true )
    {
        $validarContacto = true;
    }

	foreach ($aProvincias as $provincia )
	{
		if ( $oPersonaVO->provincia == $provincia )
		{
			$validarProvincia = true;
		}
	}

	if( ctype_alpha($oPersonaVO->localidad) ){
	    $validarLocalidad = true;
    }

	$validaciones = (
            $validarTipoUsuario
            && $validarNombreUsuario
	        && $validarContrasenia
            && $validarApellido
            && $validarNombre
            && $validarTipoDocumento
            && $validarNumeroDocumento
            && $validarSexo
            && $validarNacionalidad
            && $validarContacto
            && $validarProvincia
            && $validarLocalidad
    );

	if ( $validaciones )
	{
		$pdo = BaseDeDatos::getInstancia()->getConexion();

		$pdo->beginTransaction();

		try
		{
			$oUsuario->update();
			$oPersona->update();

			$pdo->commit();

			header('location: /tp6/administrador/');
		}
		catch(Exception $e)
		{
			$pdo->rollBack();
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="ISO-8859-1">
	<title>SGU | Editar Usuario</title>
	<link type="text/css" rel="stylesheet" href="/tp6/includes/css/estilos.css">
</head>
<body>

<div class="wraper">

	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/header.php'; ?>
	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/menu-admin.php'; ?>

	<div class="mensaje">

		<?php if ( $validaciones == true ) { ?>
			<h3>Error al registrar el usuario</h3>
			<p>
				Ha ocurrido un error al intentar registrar el usuario. Por favor intentelo nuevamente.
			</p>
		<?php } else { ?>
			<h3>Existen algunos errores al procesar la información ingresada</h3>
			<ul>
                <?php if ( $validarTipoUsuario == false ) { ?>
                 <li>Tipo de usuario no valido</li>
                <?php } if ( $validarNombreUsuario == false ) { ?>
                    <li>Solo se permiten caracteres alfanumericos en el nombre de usuario</li>
                <?php } if ( $validarContrasenia == false ) { ?>
					<li>La contraseña no es válida. Debe contener al menos 6 caracteres y al menos 1 letra y 1 número</li>
                <?php } if ( $validarApellido == false ) { ?>
                    <li>Solo se permiten caracteres alfabeticos en el apellido</li>
                <?php } if ( $validarNombre == false ) { ?>
                    <li>Solo se permiten caracteres alfabeticos en el nombre</li>
                <?php } if ( $validarTipoDocumento == false ) { ?>
					<li>El tipo de documento ingresado no se encuentra registrado</li>
                <?php } if ( $validarNumeroDocumento == false ) { ?>
                    <li>Solo se permiten numeros en el campo numero de documento</li>
				<?php } if ( $validarSexo == false ) { ?>
					<li>El sexo ingresado no se encuentra registrado</li>
                <?php } if ( $validarNacionalidad == false ) { ?>
                    <li>Solo se permiten caracteres alfabeticos en la nacionalidad</li>
                <?php } if ( $validarContacto == false ) { ?>
                    <li>Alguna de las forma de contacto no se ha ingresado correctamento, recuerde que el correo electrónico debe contener un símbolo "@" y para teléfono y celular debe contener al menos 10 dígitos y estar separado por un "-"</li>
                <?php } if ( $validarProvincia == false ) { ?>
					<li>La provincia ingresada no se encuentra registrada</li>
                <?php } if ( $validarLocalidad == false ) { ?>
                    <li>Solo se permiten caracteres alfabeticos en la localidad</li>
                <?php } ?>
			</ul>
		<?php } ?>

		<div class="buttons">
			<input type="button" value="Anterior" onclick="document.location='/tp6/administrador'">
		</div>
	</div>

	<div class="push"></div>

</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/footer.php'; ?>
</body>
</html>