<?php
	$page_title = 'Detalles de la incidencia';
	require_once ('../../modelo/load.php');
	
	// Checkin What level user has permission to view this page
	page_require_level(3);

	$idIncidencia = $_GET['idIncidencia'];
	$detalles = detallesIncidencia($idIncidencia);

	$usuario =			current_user();
	$representante =	$usuario['name'];

	ini_set ('date.timezone','America/Mexico_City');
	$fechaRes = date('Y/m/d',time());
	$horaRes =  date('H:i',time());

	if (isset($_POST['enviar']) && $_POST['enviar'] === "1") {
		$idEstatus = $_POST['status'];
		$respuesta = $_POST['mensaje'];

		$resultado = respuestaIncidencia($idIncidencia,$idEstatus,$respuesta,$representante,$fechaRes,$horaRes);

		if ($resultado)
			$session->msg("s","Éxito, la respuesta fue enviada correctamente.");
		else
			$session->msg("d","Error, algo salió mal");

		redirect("incidencias.php");
	}
?>

<?php include_once ('../layouts/header.php'); ?>

<script type="text/javascript">
	function enviar(){
		let status = document.form1.status.value;

		if (status === ""){
			alert("No olvide cambiar el estatus.");
			document.form1.status.focus();
			return -1;
		}else {
			document.form1.enviar.value = "1";
			document.form1.action = "detallesIncidencias.php?idIncidencia=<?php echo $idIncidencia ?>"
			document.form1.submit();
		}
	}
</script>

<style type="text/css">
	img {
		height: 100%;
		width: 100%;
	}
</style>

<form name="form1" method="post" action="detallesIncidencias.php">
	<div class="row col-md-9">
		<?php echo display_msg($msg); ?>
	</div>
	<div class="row col-md-9">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">
				<span class="glyphicon glyphicon-plus"></span>
				<strong> Detalles de la incidencia</strong>
			</div>
			<div class="panel-body clearfix">
				<div class="form-group">
					<div class="form-control">
						<span class="glyphicon glyphicon-user"></span>
						<label>Incidencia reportada por:</label>
					</div>
					<table class="table">
						<tr>
							<th style="width: 30%;">Empresa:</th>
							<td><?php echo $detalles['nomEmpresa'] ?></td>
						</tr>
						<tr>
							<th style="width: 30%;">Usuario:</th>
							<td><?php echo $detalles['usuario'] ?></td>
						</tr>
						<tr>
							<th style="width: 30%;">Fecha:</th>
							<td><?php echo date('d-m-Y',strtotime($detalles['fecha'])) ?></td>
						</tr>
						<tr>
							<th style="width: 30%;">Hora:</th>
							<td><?php echo $detalles['hora'].' hrs' ?></td>
						</tr>
					</table>
				</div>
				<div class="form-group">
					<div class="form-control">
						<span class="glyphicon glyphicon-file"></span>
						<label>Detalles de la Incidencia:</label>
					</div>
					<textarea name="detalles" class="form-control" maxlength="1000" rows="2" style="height: 200px; width: 100%; resize: none;" readonly><?php echo $detalles['detalles'] ?></textarea>
				</div>
				<!-- Evidencia -->
				<div class="form-group">
					<div class="form-control">
						<span class="glyphicon glyphicon-paperclip"></span>
						<label>Evidencia enviada</label>
					</div>
					<table class="table table-bordered">
						<thead><tr><td>
						<?php if (!empty($detalles['evidencias'])): ?>
							<?php if (esPDF($detalles['evidencias'])): ?>
								<embed src="data:application/pdf;base64,<?php echo base64_encode($detalles['evidencias']); ?>" width="100%" height="500px" type="application/pdf">
							<?php elseif (esImagen($detalles['evidencias'])): ?>
								<img src="data:image/jpeg;base64,<?php echo base64_encode($detalles['evidencias']) ?>">
							<?php else: ?>
								<p class="text-center">Tipo de archivo no compatible</p>
							<?php endif; ?>
						<?php else: ?>
							<p class="text-center">No se subió ninguna evidencia</p>
						<?php endif; ?>
						</td></tr></thead>
					</table>
				</div>
				<div class="form-group">
					<div class="form-control">
						<span class="glyphicon glyphicon-envelope"></span>
						<strong>Respuesta para el usuairo:</strong>
					</div>
					<textarea name="mensaje" class="form-control" maxlength="1000" rows="2" style="height: 200px; width: 100%; resize: none;"></textarea>
				</div>
				<div class="form-group">
					<div class="form-control">
						<span class="glyphicon glyphicon-tag"></span>
						<label>Modificar Status:</label>
					</div>
					<table class="table table-bordered">
						<tr>
							<td style="width: 50%;">
								<select class="form-control" name="status">
									<option value="">Estatus</option>
									<option value="1">Abierto</option>
									<option value="2">Trabajando</option>
									<option value="3">Resuelto</option>
								</select>
							</td>
							<td class="text-center" style="width: 50%;">
								<a href="#" onclick="enviar();" class="btn btn-info">
									Enviar
									<i class="glyphicon glyphicon-send"></i>
								</a>
								<input type="hidden" name="enviar" value="0">
							</td>
						</tr>
					</table>
				</div>
				<div class="form-group">
					<a href="incidencias.php" class="btn btn-danger">Regresar</a>
				</div>
			</div>
		</div>
	</div>
</form>

<?php include_once ('../layouts/footer.php'); ?>