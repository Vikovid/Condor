<?php
  $page_title = 'Clasificación de productos vendidos';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);

  ini_set('date.timezone','America/Mexico_City');
  $fechaFin = date('Y-m-d',time());
  $fechaIni = date("Y-m-d",strtotime($fechaFin."- 6 month"));
  $fechaInicial = date("d-m-Y",strtotime($fechaIni));
  $fechaFinal = date("d-m-Y",strtotime($fechaFin));

  $productos = masVendidos($fechaIni,$fechaFin);
?>
<?php include_once('../layouts/header.php'); ?>
<script type="text/javascript" src="../../libs/js/general.js"></script>
<!DOCTYPE html>
<html>
<head>
<title>Clasificación de productos vendidos</title>
</head>
<body onload="sucursal();">
  <form name="form1" method="post" action="clasifProdsVendidos.php">
  	
<span>Período:</span>
<?php echo "del $fechaInicial al $fechaFinal";?>

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
                           <h4>Clasificación de productos vendidos</h4>
                        </span>
                     </div>
                  </div>
               </div>   
            </div>   
         </div>
      </div>
   </div>
   <div class="panel-body">
      <span><strong><?php echo "Clasificación A: Más de 5 productos vendidos en el período."; ?></strong></span>
   </div>
   <div class="col-md-12">
      <div class="panel-body">
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th class="text-center" style="width: 3%;">#</th>
                  <th class="text-center" style="width: 47%;">Producto</th>
                  <th class="text-center" style="width: 10%;">Vendidos</th>
                  <th class="text-center" style="width: 10%;">Stock</th>
                  <th class="text-center" style="width: 10%;">Fecha última venta</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($productos as $producto):?>
                  <?php if ($producto['qty'] > 5){ ?>
               <tr>
                  <td class="text-center"><?php echo count_id();?></td>
                  <td> <?php echo remove_junk($producto['name']); ?></td>
                  <td class="text-center"><?php echo $producto['qty']; ?></td>
                  <td class="text-center"><?php echo $producto['quantity']; ?></td>
                  <td class="text-center"><?php echo date("d-m-Y", strtotime ($producto['fecha'])) ?></td>
               </tr>
                  <?php } ?>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   </div>
   <div class="panel-body">
      <span><strong><?php echo "Clasificación B: 3 a 5 productos vendidos en el período."; reset($productos);?></strong></span>
   </div>
   <div class="col-md-12">
      <div class="panel-body">
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th class="text-center" style="width: 3%;">#</th>
                  <th class="text-center" style="width: 47%;">Producto</th>
                  <th class="text-center" style="width: 10%;">Vendidos</th>
                  <th class="text-center" style="width: 10%;">Stock</th>
                  <th class="text-center" style="width: 10%;">Fecha última venta</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($productos as $producto):?>
                  <?php if ($producto['qty'] > 2 && $producto['qty'] < 6){ ?>
               <tr>
                  <td class="text-center"><?php echo count_id();?></td>
                  <td> <?php echo remove_junk($producto['name']); ?></td>
                  <td class="text-center"><?php echo $producto['qty']; ?></td>
                  <td class="text-center"><?php echo $producto['quantity']; ?></td>
                  <td class="text-center"><?php echo date("d-m-Y", strtotime ($producto['fecha'])) ?></td>
               </tr>
                  <?php } ?>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   </div>
   <div class="panel-body">
      <span><strong><?php echo "Clasificación C: 1 ó 2 productos vendidos en el período."; reset($productos);?></strong></span>
   </div>
   <div class="col-md-12">
      <div class="panel-body">
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th class="text-center" style="width: 3%;">#</th>
                  <th class="text-center" style="width: 47%;">Producto</th>
                  <th class="text-center" style="width: 10%;">Vendidos</th>
                  <th class="text-center" style="width: 10%;">Stock</th>
                  <th class="text-center" style="width: 10%;">Fecha última venta</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($productos as $producto):?>
                  <?php if ($producto['qty'] > 0 && $producto['qty'] < 3){ ?>
               <tr>
                  <td class="text-center"><?php echo count_id();?></td>
                  <td> <?php echo remove_junk($producto['name']); ?></td>
                  <td class="text-center"><?php echo $producto['qty']; ?></td>
                  <td class="text-center"><?php echo $producto['quantity']; ?></td>
                  <td class="text-center"><?php echo date("d-m-Y", strtotime ($producto['fecha'])) ?></td>
               </tr>
                  <?php } ?>
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
