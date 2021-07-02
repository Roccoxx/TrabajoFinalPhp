<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Singleton/BaseDeDatos.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/Singleton/Sesion.php';

$oRegistry = Sesion::getInstancia()->getRegistry();

if ($oRegistry->get('HUA') != md5($_SERVER['HTTP_USER_AGENT'])){
    header('location: /tp6/Paso1.php');
}

$pdo = BaseDeDatos::getInstancia()->getConexion();

$query = "select
		u.idusuario
		,u.nombre as usuario
		,p.apellido
		,p.nombre
		,p.numerodocumento
		,p.email
		,td.nombre as tipodocumento
		,tu.nombre as tipousuario
	from persona p
	inner join tipodocumento td using(idtipodocumento)
	inner join usuario u using(idpersona)
	inner join tipousuario tu using(idtipousuario)";

$stmt = $pdo->query($query);
$filas = $stmt->fetchAll(PDO::FETCH_OBJ);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="ISO-8859-1">
	<title>SGU | Lista de Usuarios</title>
	<link type="text/css" rel="stylesheet" href="/tp6/includes/css/estilos.css">
</head>
<body>

<div class="wraper">

	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/header.php'; ?>
	<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/menu-admin.php'; ?>

	<fieldset>
		<legend>Lista de Usuarios</legend>
		
		<table>
			
			<tr>
				<th>ID</th>
				<th>USUARIO</th>
				<th>TIPO</th>
				<th>APELLIDO Y NOMBRE</th>
				<th>DOC</th>
				<th>EMAIL</th>
				<th>ACCIONES</th>
			</tr>
			
			<?php foreach ( $filas as $fila ) { ?>
			<tr>
				<td class="text-right"><?= $fila->idusuario ?></td>
				<td><?= $fila->usuario ?></td>
				<td class="text-center"><?= $fila->tipousuario ?></td>
				<td><?= $fila->apellido ?>, <?= $fila->nombre ?></td>
				<td class="text-right">(<?= $fila->tipodocumento ?>) <?= $fila->numerodocumento ?></td>
				<td><?= $fila->email ?></td>
				<td class="text-center">
					<a href="/tp6/administrador/editar.php?id=<?= $fila->idusuario ?>" title="Editar"><img alt="Modificar" src="/tp6/includes/img/edit.png"></a>
					<a href="/tp6/administrador/eliminar.php?id=<?= $fila->idusuario ?>" title="Eliminar"><img alt="Eliminar" src="/tp6/includes/img/delete.png"></a>
				</td>
			</tr>
			<?php } ?>
			
		</table>
		
	</fieldset>
	
	<div class="push"></div>
	
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tp6/includes/php/footer.php'; ?>
</body>
</html>