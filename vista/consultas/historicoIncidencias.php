<?php
	$page_title = 'Histórico de Incidencias';
	require_once ('../../modelo/load.php');
?>

<?php include_once ('../layouts/header.php'); ?>

<form name="form1" method="post" action="historicoIncidencias.php">
	<div class="row col-md-12">
		<?php echo display_msg($msg); ?>
	</div>
	<div class="row col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">
				<div class="form-group">
					<span class="glyphicon glyphicon-folder-open"></span>
					<strong> &nbsp;Incidencias Atendidas</strong>
				</div>
				<div class="form-group">
					<div class="col-md-3">
						<select class="form-control" name="empresa">
							<option value="">Empresa</option>
						</select>
					</div>
					<div class="col-md-3">
						<select class="form-control" name="empresa">
							<option value="">Año</option>
						</select>
					</div>
					<div class="col-md-3">
						<select class="form-control" name="empresa">
							<option value="">Mes</option>
						</select>
					</div>
					<div class="col-md-3">
						<input type="submit" class="btn btn-info" value="Buscar">
					</div>
				</div>
			</div>
			<div class="panel-body clearfix">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th style="width: 30%;"> Empresa </th>
							<th style="width: 20%;"> Usuario </th>
							<th style="width: 15%;"> Fecha   </th>
							<th style="width: 15%;"> Hora    </th>
							<th style="width: 10%;"> Status  </th>
							<th style="width: 10%;"> Detalles</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td class="text-center">
								<a href="detallesHistoricoIncidencias.php" class="btn btn-success btn-xs" title="Detalles" data-toggle="tooltip">
									<span class="glyphicon glyphicon-list-alt"></span>
								</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</form>

<?php include_once ('../layouts/footer.php'); ?>