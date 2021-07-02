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

$oRegistry->add('token', md5(uniqid(rand(), true)));

$oPersonaVO = ( $oRegistry->exists('persona') == false ) ? new PersonaVO() : $oRegistry->get('persona');
$oUsuarioVO = ( $oRegistry->exists('usuario') == false ) ? new UsuarioVO() : $oRegistry->get('usuario');

$aProvincias = array('Entre Rios', 'Sante Fe', 'Cordoba', 'Buenos Aires');

$validarNombreUsuario = false;
$validarContrasenia = false;
$validarApellido = false;
$validarNombre = false;
$validarTipoDocumento = false;
$validarNumeroDocumento = false;
$validarSexo = false;
$validarNacionalidad = false;

if ( isset($_POST['bt_paso1']) == true )
{
	$oUsuarioVO->idTipoUsuario = 2;
	$oUsuarioVO->nombre = ( isset($_POST['nombre_usuario']) == true ) ? htmlentities($_POST['nombre_usuario']) : '';
	$oUsuarioVO->contrasenia = ( isset($_POST['contrasenia']) == true ) ? htmlentities($_POST['contrasenia']) : '';
	$oPersonaVO->apellido = ( isset($_POST['apellido']) == true ) ? htmlentities($_POST['apellido']) : '';
	$oPersonaVO->nombre = ( isset($_POST['nombre']) == true ) ? htmlentities($_POST['nombre']) : '';
	$oPersonaVO->idTipoDocumento = ( isset($_POST['tipo_documento']) == true ) ? htmlentities($_POST['tipo_documento']) : '';
	$oPersonaVO->numeroDocumento = ( isset($_POST['numero_documento']) == true ) ? htmlentities($_POST['numero_documento']) : '';
	$oPersonaVO->sexo = ( isset($_POST['sexo']) == true ) ? htmlentities($_POST['sexo']) : '';
	$oPersonaVO->nacionalidad = ( isset($_POST['nacionalidad']) == true ) ? htmlentities($_POST['nacionalidad']) : '';

	$oUsuario = ActiveRecordFactory::getUsuario();
	$oUsuario->set($oUsuarioVO);

	if(ctype_alnum($oUsuarioVO->nombre)){
        $validarNombreUsuario = true;
    }

	if ( $oUsuario->validarContrasenia() == true )
	{
		$validarContrasenia = true;
	}

    if(ctype_alpha($oPersonaVO->apellido)){
        $validarApellido = true;
    }

    if(ctype_alpha($oPersonaVO->nombre)){
        $validarNombre = true;
    }

	foreach ( ActiveRecordFactory::getTipoDocumento()->fetchAll() as $oTipoDocumento )
	{
		if ( $oPersonaVO->idTipoDocumento == $oTipoDocumento->idTipoDocumento )
		{
			$validarTipoDocumento = true;
		}
	}

	if(ctype_digit($oPersonaVO->numeroDocumento)){
        $validarNumeroDocumento = true;
    }

	$sexos = array('M','F');
	if ( in_array($oPersonaVO->sexo, $sexos) == true )
	{
		$validarSexo = true;
	}

	if(ctype_alpha($oPersonaVO->nacionalidad)){
	    $validarNacionalidad = true;
    }

	$oRegistry->add('persona', $oPersonaVO);
	$oRegistry->add('usuario', $oUsuarioVO);
}
else
{
    $validarNombreUsuario = true;
    $validarContrasenia = true;
    $validarApellido = true;
    $validarNombre = true;
    $validarTipoDocumento = true;
    $validarNumeroDocumento = true;
    $validarSexo = true;
    $validarNacionalidad = true;
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

	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/header.php' ?>
	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/menu.php'; ?>

	<?php if ( !CheckValidation(
	            $validarNombreUsuario, $validarContrasenia, $validarApellido, $validarNombre,
                $validarTipoDocumento, $validarNumeroDocumento, $validarSexo, $validarNacionalidad
                )
            )
	{ ?>

		<div class="mensaje">
			<h3>Existen algunos errores al procesar la información ingresada</h3>
			<ul>
				<?php
                PrintValidation(
                    $validarNombreUsuario, $validarContrasenia, $validarApellido, $validarNombre,
                    $validarTipoDocumento, $validarNumeroDocumento, $validarSexo, $validarNacionalidad
                );
                ?>
			</ul>
			<div class="buttons">
				<input type="button" value="Anterior" onclick="document.location='Paso1.php'">
			</div>
		</div>

	<?php } else { ?>

		<form action="Paso3.php" method="post">
			<fieldset>
				<legend>Informaci&oacute;n de Contacto:</legend>

				<ul>
                    <input type="hidden" name="token" value="<?php echo $oRegistry->get('token'); ?>" />
					<li><label>Correo electr&oacute;nico:</label></li>
					<li><input type="text" name="email" value="<?= $oPersonaVO->email ?>"></li>

					<li><label>Tel&eacute;fono:</label></li>
					<li><input type="text" name="telefono" value="<?= $oPersonaVO->telefono ?>"></li>

					<li><label>Celular:</label></li>
					<li><input type="text" name="celular" value="<?= $oPersonaVO->celular ?>"></li>

					<li><label>Provincia:</label></li>
					<li>
						<select name="provincia">
							<?php foreach ($aProvincias as $provincia ) { ?>
							<option value="<?= $provincia ?>" <?= ( $oPersonaVO->provincia == $provincia ) ? 'selected="selected"' : ''  ?>><?= $provincia ?></option>
							<?php } ?>
						</select>
					</li>

					<li><label>Localidad:</label></li>
					<li><input type="text" name="localidad" value="<?= $oPersonaVO->localidad ?>"></li>
				</ul>

				<div class="buttons">
					<input type="submit" name="bt_paso2" value="Siguiente">
					<input type="button" value="Anterior" onclick="document.location='Paso1.php'">
				</div>
			</fieldset>
		</form>

	<?php } ?>

	<div class="push"></div>
	
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/footer.php'; ?>
</body>
</html>

<?php

function CheckValidation($validarNombreUsuario, $validarContrasenia, $validarApellido, $validarNombre,
                         $validarTipoDocumento, $validarNumeroDocumento, $validarSexo, $validarNacionalidad)
{
    if($validarNombreUsuario == false
        || $validarContrasenia == false
        || $validarApellido == false
        || $validarNombre == false
        || $validarTipoDocumento == false
        || $validarNumeroDocumento == false
        || $validarSexo == false
        || $validarNacionalidad == false
    ) return false;

    return true;
}

function PrintValidation($validarNombreUsuario, $validarContrasenia, $validarApellido, $validarNombre,
                         $validarTipoDocumento, $validarNumeroDocumento, $validarSexo, $validarNacionalidad)
{
    if ( $validarNombreUsuario == false ) {
        echo "<li>Solo caracteres alfanumericos se permiten en el usuario</li>";
    }
    if ( $validarContrasenia == false ) {
        echo "<li>La contraseña no es válida. Debe contener al menos 6 caracteres y al menos 1 letra y 1 número</li>";
    }
    if ( $validarApellido == false ) {
        echo "<li>Solo caracteres alfabeticos se permiten en el apellido</li>";
    }
    if ( $validarNombre == false ) {
        echo "<li>Solo caracteres alfabeticos se permiten en el nombre</li>";
    }
    if ( $validarTipoDocumento == false ) {
        echo "<li>El tipo de documento ingresado no se encuentra registrado</li></html>";
    }
    if ( $validarNumeroDocumento == false ) {
        echo "<li>Solo caracteres numericos se permiten en el documento</li>";
    }
	if ( $validarSexo == false ) {
	    echo "<li>El sexo ingresado no se encuentra registrado</li>";
	}
    if ( $validarNacionalidad == false ) {
        echo "<li>Solo caracteres alfabeticos se permiten en la nacionalidad</li>";
    }
}

?>