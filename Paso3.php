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

$oPersonaVO = ( $oRegistry->exists('persona') == false ) ? new PersonaVO() : $oRegistry->get('persona');
$oUsuarioVO = ( $oRegistry->exists('usuario') == false ) ? new UsuarioVO() : $oRegistry->get('usuario');

$aProvincias = array('Entre Rios', 'Sante Fe', 'Cordoba', 'Buenos Aires');

$validarProvincia = false;
$validarContacto = false;
$validarLocalidad = false;

if ( isset($_POST['bt_paso2']) == true )
{
	$oPersonaVO->email = ( isset($_POST['email']) == true ) ? htmlentities($_POST['email']) : '';
	$oPersonaVO->telefono = ( isset($_POST['telefono']) == true ) ? htmlentities($_POST['telefono']) : '';
	$oPersonaVO->celular = ( isset($_POST['celular']) == true ) ? htmlentities($_POST['celular']) : '';
	$oPersonaVO->provincia = ( isset($_POST['provincia']) == true ) ? htmlentities($_POST['provincia']) : '';
	$oPersonaVO->localidad = ( isset($_POST['localidad']) == true ) ? htmlentities($_POST['localidad']) : '';

	foreach ($aProvincias as $provincia )
	{
		if ( $oPersonaVO->provincia == $provincia )
		{
			$validarProvincia = true;
		}
	}

	$oPersona = ActiveRecordFactory::getPersona();
	$oPersona->set($oPersonaVO);

	if ( $oPersona->validarContactos() == true )
	{
		$validarContacto = true;
	}

    if ( ctype_alpha($oPersonaVO->localidad) )
    {
        $validarLocalidad = true;
    }

	$oRegistry->add('persona', $oPersonaVO);
}
else
{
	$validarProvincia = true;
	$validarContacto = true;
    $validarLocalidad = true;
}

$oPersona = ActiveRecordFactory::getPersona();
$oPersona->set($oPersonaVO);
$oUsuario = ActiveRecordFactory::getUsuario();
$oUsuario->set($oUsuarioVO);
$oTipoDocumento = ActiveRecordFactory::getTipoDocumento();
$oTipoDocumento->fetch($oPersonaVO->idTipoDocumento);

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

	<?php if ( $validarProvincia == false || $validarContacto == false || $validarLocalidad == false ) { ?>

		<div class="mensaje">
			<h3>Existen algunos errores al procesar la información ingresada</h3>
			<ul>
				<?php if ( $validarProvincia == false ) { ?>
					<li>La provincia ingresada no se encuentra registrada</li>
				<?php } if ( $validarContacto == false ) { ?>
					<li>Alguna de las forma de contacto no se ha ingresado correctamento, recuerde que el correo electrónico debe contener un símbolo "@" y para teléfono y celular debe contener al menos 10 dígitos y estar separado por un "-"</li>
                <?php } if ( $validarLocalidad == false ) { ?>
                    <li>Solo se permiten caracteres alfabeticos para la localidad</li>
                <?php } ?>
			</ul>
			<div class="buttons">
				<input type="button" value="Anterior" onclick="document.location='Paso2.php'">
			</div>
		</div>

	<?php } else { ?>

		<h2>Informaci&oacute;n de alta de usuario</h2>

		<div class="ultimo_paso">

			<fieldset>
				<legend>Informaci&oacute;n Personal:</legend>

				<ul>
					<li><label>Nombre de Usuario:</label></li>
					<li><?= $oUsuarioVO->nombre ?><br></li>

					<li><label>Contrase&ntilde;a:</label></li>
					<li><?= $oUsuario->getContraseniaEnmascadara() ?><br></li>

					<li><label>Apellido:</label></li>
					<li><?= $oPersonaVO->apellido ?></li>

					<li><label>Nombre:</label></li>
					<li><?= $oPersonaVO->nombre ?></li>

					<li><label>Tipo de Documento:</label></li>
					<li><?= $oTipoDocumento->get()->nombre ?></li>

					<li><label>N&uacute;mero de Documento:</label></li>
					<li><?= $oPersonaVO->numeroDocumento ?></li>

					<li><label>Sexo:</label></li>
					<li><?= ( $oPersonaVO->sexo == 'M' ) ? 'Masculino' : 'Femenino' ?></li>

					<li><label>Nacionalidad:</label></li>
					<li><?= $oPersonaVO->nacionalidad ?></li>
				</ul>

			</fieldset>

			<fieldset>
				<legend>Informaci&oacute;n de Contacto:</legend>

				<ul>
					<li><label>Correo electr&oacute;nico:</label></li>
					<li><?= $oPersonaVO->email ?></li>

					<li><label>Tel&eacute;fono:</label></li>
					<li><?= $oPersonaVO->telefono ?></li>

					<li><label>Celular:</label></li>
					<li><?= $oPersonaVO->celular ?></li>

					<li><label>Provincia:</label></li>
					<li><?= $oPersonaVO->provincia ?></li>

					<li><label>Localidad:</label></li>
					<li><?= $oPersonaVO->localidad ?></li>
				</ul>

			</fieldset>

			<fieldset>

				<div class="buttons">
					<input type="button" value="Guardar" onclick="document.location='Finalizar.php'">
					<input type="button" value="Anterior" onclick="document.location='Paso2.php'">
				</div>

			</fieldset>

		</div>

	<?php } ?>
	
	<div class="push"></div>
	
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/footer.php'; ?>
</body>
</html>