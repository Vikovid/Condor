<?php
  $page_title = 'Lista de productos menos vendidos';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);

  ini_set('date.timezone','America/Mexico_City');
  $fechaIni = fechaMovMin();
  $fechaInicial = $fechaIni['fechaMod'];
  $fechaIni = date("Y-m-d",strtotime($fechaInicial));
  $fechaFin = date('Y-m-d',time());
  $fechaFin = date("Y-m-d",strtotime($fechaFin."- 1 year"));
  $fechaFin = date("Y-m-d",strtotime($fechaFin."- 1 day"));

  $productos = menosVendidos($fechaIni,$fechaFin);
?>
<?php include_once('../layouts/header.php'); ?>
<!DOCTYPE html>
<html>
<head>
<title>Lista de productos menos vendidos</title>
</head>
<body onload="sucursal();">
  <form name="form1" method="post" action="masVendidos.php">
  	
<div class="row">
   <div class="col-md-12">
      <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-12">
      <div class="panel panel-default">
         <div class="panel-heading clearfix">
            <div class="pull-right">
               <div class="form-group">
                  <div class="col-md-4">
                     <div class="input-group">
                        <span class="input-group-addon">
                           <h3>Productos menos vendidos</h3>
                        </span>
                     </div>
                  </div>
               </div>   
            </div>   
         </div>
      </div>
   </div>
   <div class="col-md-12">
      <div class="panel-body">
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th class="text-center" style="width: 3%;">#</th>
                  <th class="text-center" style="width: 47%;">Producto</th>
                  <th class="text-center" style="width: 10%;">Stock</th>
                  <th class="text-center" style="width: 10%;">Fecha último movimiento</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($productos as $producto):?>
               <tr>
                  <td class="text-center"><?php echo count_id();?></td>
                  <td> <?php echo remove_junk($producto['name']); ?></td>
                  <td class="text-center"><?php echo $producto['quantity']; ?></td>
                  <td class="text-center"><?php echo date("d-m-Y", strtotime ($producto['fecha'])) ?></td>
               </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   </div>
</div>
</form>
</body>
</html>
<?php include_once('../layouts/footer.php'); ?>
