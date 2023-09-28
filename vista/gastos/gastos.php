<?php
  	$page_title = 'Lista de gastos';
  	require_once('../../modelo/load.php');
  	page_require_level(1);

   $meses = array('01'=>'Enero',
                  '02'=>'Febrero',
                  '03'=>'Marzo',
                  '04'=>'Abril',
                  '05'=>'Mayo' ,
                  '06'=>'Junio',
                  '07'=>'Julio',
                  '08'=>'Agosto',
                  '09'=>'Septiembre',
                  '10'=>'Octubre',
                  '11'=>'Noviembre',
                  '12'=>'Diciembre');
                    
  	$factura = "";
  	$mes = "";
  	$anio = "";
  	$dia = "";
  
  	if(isset($_POST['factura']))
    	$factura =  remove_junk($db->escape($_POST['factura']));
  	if(isset($_POST['dia']))
    	$dia =  remove_junk($db->escape($_POST['dia']));
  	if(isset($_POST['mes']))
    	$mes =  remove_junk($db->escape($_POST['mes']));
  	if(isset($_POST['anio']))
    	$anio =  remove_junk($db->escape($_POST['anio']));

	if ($mes == "" && $anio == "" && $dia == ""){
     	$year = date('Y');           
     	$fechaInicial = $year."/01/01";
     	$fechaFinal = date('Y/m/d',time());
  	}
  	if ($mes == "" && $anio == "" && $dia != ""){
     	$year = date('Y');
     	$month = date('m');
     	$fechaInicial = $year."/".$month."/".$dia;
     	$fechaFinal = $year."/".$month."/".$dia;
  	}
  	if ($mes == "" && $anio != "" && $dia == ""){
     	$month = date('m');
     	$day = date('d');
     	$fechaInicial = $anio."/01/01";
     	$fechaFinal = $anio."/".$month."/".$day;
  	}
  	if ($mes == "" && $anio != "" && $dia != ""){
     	$month = date('m');
     	$fechaInicial = $anio."/".$month."/".$dia;
     	$fechaFinal = $anio."/".$month."/".$dia;
  	}
  	if ($mes != "" && $anio == "" && $dia == ""){
     	$year = date('Y');
     	$day = date('d');
     	$fechaInicial = $year."/".$mes."/01/";
     	$numDias = date('t', strtotime($fechaInicial));
     	$fechaFinal = $year."/".$mes."/".$numDias;
  	}
  	if ($mes != "" && $anio == "" && $dia != ""){
     	$year = date('Y');
     	$fechaInicial = $year."/".$mes."/".$dia;
     	$fechaFinal = $year."/".$mes."/".$dia;
  	}
  	if ($mes != "" && $anio != "" && $dia == ""){
     	$fechaInicial = $anio."/".$mes."/01";
     	$numDias = date('t', strtotime($fechaInicial));
     	$fechaFinal = $anio."/".$mes."/".$numDias;
  	}
  	if ($mes != "" && $anio != "" && $dia != ""){
     	$fechaInicial = $anio."/".$mes."/".$dia;
     	$fechaFinal = $anio."/".$mes."/".$dia;
  	}

  	$fechaIni = date('Y/m/d', strtotime($fechaInicial));
  	$fechaFin = date("Y/m/d", strtotime($fechaFinal));

  	if ($factura != ""){
     	$gasto = gastosFactura($factura,$fechaIni,$fechaFin);
  	}else{
     	$gasto = join_gastos_table2($fechaIni,$fechaFin);
  	}
?>
<?php include_once('../layouts/header.php'); ?>
<script language="Javascript">
	function gastos(){
		document.form1.action = "gastos.php";
		document.form1.submit();
	}
	function excel(){
		document.form1.action = "../excel/excelgastos.php";
		document.form1.submit();
	}
	function foco(){
	  	document.form1.factura.focus();
	}
	function diasMes() {
	  	var anio = "";
	  	var mes = "";
	  	var hoy = new Date();
	  	var dia = "";
	  	var array = [];

	  	anio = document.form1.anio.value;
	  	mes = document.form1.mes.value;

	  	if (anio == "")
	     	anio = hoy.getFullYear();
	  
	  	if (mes == ""){
	     	mes = hoy.getMonth() + 1;
	     	if (mes < 10)
	        	mes = "0" + mes;
	  	}

	  	var numDias = new Date(anio, mes, 0).getDate();

	  	for (var d = 1;d <= numDias; d++){
	     	if (d < 10)
	       	dia = "0" + d;
	     	else
	       	dia = d;

	     	array.push(dia);
	  	}
	  	addOptions("dia", array);
	}
	function addOptions(domElement, array) {
	  	var select = document.getElementsByName(domElement)[0];
	  	var option;

	  	for (value in array) {
	     	option = document.createElement("option");
	     	option.text = array[value];
	     	select.add(option);
	  	}
	}
</script>

<body onload="foco();diasMes();">
<form name="form1" method="post" action="gastos.php">
  	<div class="row">
     	<div class="col-md-12">
       	<?php echo display_msg($msg); ?>
     	</div>
	   <div class="col-md-12">
	      <div class="panel panel-default">
	         <div class="panel-heading clearfix">
	            <div class="form-group">
		            <!-- FACTURA -->
		            <div class="col-md-3">
		               <div class="input-group">
		                  <span class="input-group-addon">
		                     <i class="glyphicon glyphicon-list-alt"></i>
		                  </span>
		                  <input type="text" class="form-control" name="factura" placeholder="Factura">
		               </div>
		            </div>
		            <!-- AÑO -->
		            <div class="col-md-2">
		               <select class="form-control" name="anio">
		                  <option value="">Selecciona un año</option>
		                  <?php $i = (int)2020; while($i <= 2040): ?>
		                   	<option value="<?php echo $i ?>"> <?php echo $i; ?> </option>
		                  <?php $i++; endwhile; ?>
		               </select>
		            </div>
		            <!-- MES -->
		            <div class="col-md-2">
		               <select class="form-control" name="mes">
		                  <option value=""  >Selecciona un mes</option>
		                  <?php while(key($meses)): ?>
		                    	<option value="<?php echo key($meses); ?>"> <?php echo remove_junk($meses[key($meses)]); ?> </option>
		                  <?php next($meses); endwhile;?>
		               </select>
		            </div>
		            <!-- DÍA -->
		            <div class="col-md-2">
		               <select class="form-control" name="dia">
		                  <option value="">Selecciona un día</option>
		               </select>                
		            </div>
		            <!-- BOTONES -->
		            <a href="#" onclick="gastos();" class="btn btn-primary">Buscar</a>&nbsp;
		            <a href="add_gastos.php" class="btn btn-primary">Agregar Gastos</a>&nbsp;
		            <a href="#" onclick="excel();" class="btn btn-xs btn-success">Excel</a>&nbsp;      
		            <img src="../../libs/imagenes/Logo.png" height="50" width="50" alt="" align="center">
		         </div>
	         </div>
	        	<div class="panel-body">
	          	<table class="table table-bordered">
	            	<thead>
	              		<tr>
	                		<th class="text-center" style="width: 9%;" > Factura </th>                
	                		<th class="text-center" style="width: 17%;"> Proveedor </th>
	                		<th class="text-center" style="width: 17%;"> Descripción </th>
	                		<th class="text-center" style="width: 14%;"> Categoría </th>
	                		<th class="text-center" style="width: 7%;" > Subtotal </th>
	                		<th class="text-center" style="width: 7%;" > IVA </th>
	                		<th class="text-center" style="width: 7%;" > Total </th>
	                		<th class="text-center" style="width: 9%;" > Forma de Pago </th>
	                		<th class="text-center" style="width: 9%;" > Fecha </th>
	                		<th class="text-center" style="width: 5%;" > Acciones </th>
	              		</tr>
	            	</thead>
	            	<tbody>
	              	<?php foreach ($gasto as $gasto): ?>
	              		<tr>
	                		<td> <?php echo remove_junk($gasto['factura']); ?></td>
	                		<td> <?php echo remove_junk($gasto['nom_proveedor']); ?></td>
	                		<td> <?php echo remove_junk($gasto['descripcion']); ?></td>
	                		<td> <?php echo remove_junk($gasto['name']); ?></td>
	                		<td class="text-right"><?php echo remove_junk($gasto['monto']); ?></td>
	                		<td class="text-right"><?php echo remove_junk($gasto['iva']); ?></td>
	                		<td class="text-right"><?php echo remove_junk($gasto['total']); ?></td>
	                		<td class="text-center"><?php echo remove_junk($gasto['tipo_pago']); ?></td>
	                		<td class="text-center"><?php echo date("d-m-Y", strtotime ($gasto['fecha'])); ?></td>
	                		<td class="text-center">
		                  	<div class="btn-group">
		                    		<a href="edit_gasto.php?id=<?php echo (int)$gasto['id'];?>&idProveedor=<?php echo (int)$gasto['idProveedor'];?>&idCategoria=<?php echo (int)$gasto['categoria'];?>&id_pago=<?php echo (int)$gasto['id_pago'];?>" class="btn btn-info btn-xs"  title="Editar" data-toggle="tooltip">
		                      		<span class="glyphicon glyphicon-edit"></span>
		                    		</a>
		                     	<a href="delete_gasto.php?id=<?php echo (int)$gasto['id'];?>" class="btn btn-danger btn-xs"  title="Eliminar" data-toggle="tooltip">
		                      		<span class="glyphicon glyphicon-trash"></span>
		                    		</a>
		                  	</div>
	                		</td>
	              		</tr>
	             	<?php endforeach; ?>
	            	</tbody>
	          	</table>
	        	</div>
	      </div>
	   </div>
  	</div>
</form>
</body>

<?php include_once('../layouts/footer.php'); ?>