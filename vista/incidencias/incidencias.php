<?php
	$page_title = 'Incidencias por atender';
	require_once ('../../modelo/load.php');

	// Checkin What level user has permission to view this page
	page_require_level(3);

	$incidencias = incidenciaSinAtender();
?>

<?php include_once ('../layouts/header.php'); ?>

<form name="form1" method="post" action="incidencias.php">
	<div class="row col-md-12">
		<?php echo display_msg($msg); ?>
	</div>
	<div class="row col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">
				<span class="glyphicon glyphicon-bell"></span>
				<strong> Incidencias sin atender</strong>
			</div>
			<div class="panel-body clearfix">
				<?php if (!empty($incidencias)): ?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th style="width: 20%;"> Empresa </th>
							<th style="width: 20%;"> Usuario </th>
							<th class="text-center" style="width: 15%;"> Fecha   </th>
							<th class="text-center" style="width: 10%;"> Hora    </th>
							<th class="text-center" style="width: 15%;"> Status  </th>
							<th class="text-center" style="width: 10%;"> Detalles</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($incidencias as $inci): ?>
						<tr>
							<td><?php echo $inci['nomEmpresa'] ?></td>
							<td><?php echo $inci['usuario'] ?></td>
							<td class="text-center"><?php echo date('d-m-Y',strtotime($inci['fecha'])) ?></td>
							<td class="text-center"><?php echo $inci['hora'].' hrs' ?></td>
							<td class="text-center"><?php echo $inci['estatus'] ?></td>
							<td class="text-center">
								<a href="detallesIncidencias.php?idIncidencia=<?php echo $inci['id'] ?>" class="btn btn-success btn-xs" title="Detalles" data-toggle="tooltip">
									<span class="glyphicon glyphicon-list-alt"></span>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<?php else: ?>
				<table class="table table-bordered"><tr><td class="text-center">
					<label>No hay incidencias por atender.</label>
				</td></tr></table>
				<?php endif; ?>
			</div>
		</div>
	</div>
</form>

<?php include_once ('../layouts/footer.php'); ?>