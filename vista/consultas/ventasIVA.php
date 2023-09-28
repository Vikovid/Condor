<?php
	require_once ('../../modelo/load.php');
	page_require_level(1);
	date_default_timezone_set('America/Mexico_City');	
	$page_title = 'Ventas con IVA';

	$meses = array (
    	'01' => 'Enero',
    	'02' => 'Febrero',
    	'03' => 'Marzo',
    	'04' => 'Abril',
    	'05' => 'Mayo',
    	'06' => 'Junio',
    	'07' => 'Julio',
    	'08' => 'Agosto',
    	'09' => 'Septiembre',
    	'10' => 'Octubre',
    	'11' => 'Noviembre',
    	'12' => 'Diciembre'
   );

	$producto =  isset($_POST['producto']) ? $_POST['producto']:'';

	$anio = 		 (isset($_POST['anio']) && $_POST['anio'] != '') ? 	$_POST['anio']: date('Y');
	$mes 	= 		 (isset($_POST['mes']) && $_POST['mes'] != '') 	?  $_POST['mes']: date('m');
	$fechaIni =  date($anio.'-'.$mes.'-01');
	$fechaFin =  date($anio.'-'.$mes.'-t',strtotime($fechaIni));

	$iva = 		 isset($_POST['iva']) ? $_POST['iva']:'';

	$productos = buscaPorIVA($producto,$fechaIni,$fechaFin,$iva);
?>

<?php include_once ('../layouts/header.php'); ?>

<script type="text/javascript" src="../../libs/js/general.js"></script>
<script type="text/javascript">
	function buscar () {
		document.form1.action = "ventasIVA.php";
		document.form1.submit();
	}
</script>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>

<body onload="valor();">
	<form name="form1" method="POST" action="ventasIVA.php">
		<div class="row col-md-12">
			<?php echo "Periodo del: $fechaIni al $fechaFin"?>
		</div>
		<div class="row col-md-12">
			<div class="panel panel-default">
				<!-- FILTROS -->
				<div class="panel-heading clearfix">
					<div class="form-group">
						<div class="col-md-3">
							<input class="form-control" placeholder="PRODUCTO" type="text" name="producto" id="busqueda" oninput="mayusculas(event)">
						</div>
						<div class="col-md-2">
							<select class="form-control" name="anio">
								<option value="">Año</option>
								<?php $anio = (int)2020; while ($anio <= 2040): ?>
									<option value="<?php echo $anio ?>"><?php echo $anio ?></option>
								<?php $anio++; endwhile; ?>
							</select>
						</div>
						<div class="col-md-2">
							<select class="form-control" name="mes">
								<option value="">Mes</option>
								<?php foreach ($meses as $mes => $nombreMes): ?>
									<option value="<?php echo $mes ?>"><?php echo $nombreMes ?></option>
								<?php endforeach ?>
							</select>
						</div>
						<div class="col-md-2">
							<select name="iva" class="form-control">
								<option value="">IVA</option>
								<option value="1">Con IVA</option>
								<option value="0">Sin IVA</option>
							</select>
						</div>
						<div class="col-md-2">
							<a href="#" class="btn btn-primary" name="Buscar" onclick="buscar();">Buscar</a>
						</div>
					</div>
				</div>
				<!-- TABLA -->
				<div class="panel-body">
					<table class="table table-bordered table-triped">
						<thead>
							<tr>
								<th>Cliente</th>
								<th class="text-center">Producto</th>
								<th>Cantidad</th>
								<th>Subtotal</th>
								<th class="text-center">IVA</th>
								<th class="text-center">Total</th>
								<th class="text-center">Forma de Pago</th>
								<th>Fecha</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($productos as $prod): 
                        $sqlNumPagos  =	"SELECT count(id_pago) AS numPagos FROM pagos ";
                        $sqlNumPagos .=   "WHERE id_ticket = '{$prod['id_ticket']}'";
                        $respNumPagos =   $db->query($sqlNumPagos);
                        $consNumPagos =   mysqli_fetch_assoc($respNumPagos);
                        $numPagos =       $consNumPagos['numPagos'];

                        if ($numPagos == "1") {
                           $consTipoPago =   buscaRegistroPorCampo('pagos','id_ticket',$prod['id_ticket']);
                           $idTipoPago =     $consTipoPago['id_tipo'];

                           if ($idTipoPago == "1")
                              $tipoPago = "Efectivo";
                           if ($idTipoPago == "2")
                              $tipoPago = "Transferencia";
                           if ($idTipoPago == "3")
                              $tipoPago = "Deposito";
                           if ($idTipoPago == "4")
                              $tipoPago = "Tarjeta";
                           } else
                              $tipoPago = "Mixto";
							?>
								<tr>
									<td><?php echo $prod['cliente'];?></td>
									<td><?php echo utf8_encode($prod['producto']);?></td>
									<td class="text-center align-items-center"><?php echo (int)$prod['cantidad'];?></td>
									<td class="text-center align-items-center"><?php echo '$'.money_format('%2.n', $prod['subtotal']) ?></td>
									<?php if ($prod['total'] == 0) {?>
										<td class="text-center align-items-center"><?php echo "$0.00" ?></td>
										<td class="text-center align-items-center"><?php echo '$'.money_format('%.2n',$prod['subtotal']) ?></td>
									<?php } else { ?>
										<td class="text-center align-items-center"><?php echo '$'.money_format('%.2n',$prod['total']-$prod['subtotal'])?></td>
										<td class="text-center align-items-center"><?php echo '$'.money_format('%.2n',$prod['total']) ?></td>
									<?php } ?>
									<td><?php echo $tipoPago ?></td>
									<td class="text-center align-items-center"><?php echo $prod['fecha'] ?></td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</form>
</body>
</html>

<?php include_once ('../layouts/footer.php'); ?>