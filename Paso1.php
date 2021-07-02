<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Factory/ActiveRecordFactory.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Singleton/Sesion.php';

$oRegistry = Sesion::getInstancia()->getRegistry();

$oPersonaVO = ( $oRegistry->exists('persona') == false ) ? new PersonaVO() : $oRegistry->get('persona');
$oUsuarioVO = ( $oRegistry->exists('usuario') == false ) ? new UsuarioVO() : $oRegistry->get('usuario');
$aTipoDocumento = ActiveRecordFactory::getTipoDocumento()->fetchAll();

$oRegistry->add('token', md5(uniqid(rand(), true)));
$oRegistry->add('HUA', md5($_SERVER['HTTP_USER_AGENT']));

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

	<form action="Paso2.php" method="post">
		<fieldset>
			<legend>Informaci&oacute;n Personal:</legend>
			
			<ul>
                <input type="hidden" name="token" value="<?php echo $oRegistry->get('token'); ?>" />
				<li><label>Nombre de Usuario:</label></li>
				<li><input type="text" name="nombre_usuario" value="<?= $oUsuarioVO->nombre ?>"></li>
				
				<li><label>Contrase&ntilde;a:</label></li>
				<li><input type="password" name="contrasenia" value="<?= $oUsuarioVO->contrasenia ?>"></li>
				
				<li><label>Apellido:</label></li>
				<li><input type="text" name="apellido" value="<?= $oPersonaVO->apellido ?>"></li>
				
				<li><label>Nombre:</label></li>
				<li><input type="text" name="nombre" value="<?= $oPersonaVO->nombre ?>"></li>
				
				<li><label>Tipo de Documento:</label></li>
				<li>
					<select name="tipo_documento">
						<?php foreach ( $aTipoDocumento as $oTipoDocumento ) { ?>
						<option value="<?= $oTipoDocumento->idTipoDocumento ?>" <?= ( $oPersonaVO->idTipoDocumento == $oTipoDocumento->idTipoDocumento ) ? 'selected="selected"' : ''  ?>><?= $oTipoDocumento->nombre ?></option>
						<?php } ?>
					</select>
				</li>
				
				<li><label>N&uacute;mero de Documento:</label></li>
				<li><input type="text" name="numero_documento" value="<?= $oPersonaVO->numeroDocumento ?>"></li>
				
				<li><label>Sexo:</label></li>
				<li>
					<label class="radio"><input type="radio" name="sexo" value="M" <?= ( $oPersonaVO->sexo == 'M' ) ? 'checked="checked"' : ''  ?>> Masculino</label>
					<label class="radio"><input type="radio" name="sexo" value="F" <?= ( $oPersonaVO->sexo == 'F' ) ? 'checked="checked"' : ''  ?>> Femenino</label>
				</li>
				
				<li><label>Nacionalidad:</label></li>
				<li><input type="text" name="nacionalidad" value="<?= $oPersonaVO->nacionalidad ?>"></li>
			</ul>
			
			<div class="buttons">
				<input type="submit" name="bt_paso1" value="Siguiente">
			</div>
		</fieldset>
	</form>
	
	<div class="push"></div>
	
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/footer.php'; ?>
</body>
</html>