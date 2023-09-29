<?php
	$page_title = 'Incidencias por atender';
	require_once ('../../modelo/load.php');
?>

<?php include_once ('../layouts/header.php'); ?>

<form name="form1" method="post" action="incidencias.php">
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
					<table class="table">
						<tr>
							<th style="width: 30%;">Empresa:</th>
							<td>Patito Modosito S.A. de C.V.</td>
						</tr>
						<tr>
							<th style="width: 30%;">Usuario:</th>
							<td>Roberto Carlos</td>
						</tr>
						<tr>
							<th style="width: 30%;">Fecha:</th>
							<td>0000-00-00</td>
						</tr>
						<tr>
							<th style="width: 30%;">Hora:</th>
							<td>00:00</td>
						</tr>
					</table>
				</div>
				<div class="form-group">
					<div class="form-control">
						<span class="glyphicon glyphicon-file"></span>
						<label>Detalles de la Incidencia:</label>
					</div>
					<textarea name="detalles" class="form-control" maxlength="1000" rows="2" style="height: 200px; width: 100%; resize: none;" readonly>Está mal :c</textarea>
				</div>
				<div class="form-group">
					<div class="form-control">
						<span class="glyphicon glyphicon-envelope"></span>
						<strong>Detalles para el usuairo:</strong>
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
									<option value="">status</option>
									<option value="">Observación</option>
									<option value="">Trabajando</option>
									<option value="">Resuelto</option>
								</select>
							</td>
							<td class="text-center" style="width: 50%;">
								<a href="#" class="btn btn-info">Enviar</a>
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