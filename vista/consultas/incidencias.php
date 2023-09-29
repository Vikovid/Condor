<?php
	$page_title = 'Incidencias por atender';
	require_once ('../../modelo/load.php');
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
				<table class="table table-bordered">
					<thead>
						<tr>
							<th style="width: 20%;"> Empresa </th>
							<th style="width: 20%;"> Usuario </th>
							<th style="width: 15%;"> Fecha   </th>
							<th style="width: 10%;"> Hora    </th>
							<th style="width: 10%;"> Detalles</th>
							<th style="width: 15%;"> Status  </th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td class="text-center">
								<a href="detallesIncidencias.php" class="btn btn-success btn-xs" title="Detalles" data-toggle="tooltip">
									<span class="glyphicon glyphicon-list-alt"></span>
								</a>
							</td>
							<td>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</form>

<?php include_once ('../layouts/footer.php'); ?>