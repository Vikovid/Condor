<?php
  $page_title = 'Lista de aplicaciones por vencer';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  ini_set('date.timezone','America/Mexico_City');
?>
<?php include_once('../layouts/header.php'); ?>
<script type="text/javascript" src="../../libs/js/general.js"></script>

<!DOCTYPE html>
<html>
<head>
<title>Lista de productos con garantía</title>
</head>

<body onload="focoCliente();">
  <form name="form1" method="post" action="prodsSinGarantia.php">
          <br>
<?php

  $cliente = isset($_POST['cliente']) ? $_POST['cliente']:'';
 
  if ($cliente != "") {
     $garantias = sinGarantiasCliente($cliente);
  }else{
     $garantias = sinGarantias();
  }

?>
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
                          <i class="glyphicon glyphicon-barcode"></i>
                       </span>
                       <input type="text" class="form-control" name="cliente" long="21">
                    </div>
                 </div>  
                 <a href="#" onclick="prodsSinGarantia();" class="btn btn-primary">Buscar</a> 
                 <strong>
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Productos sin garantía</span>
                 </strong>
              </div>   
           </div>   
        </div>
     </div>
     <div class="panel-body">
        <table class="table table-bordered">
           <thead>
              <tr>
                 <th class="text-center" style="width: 18%;"> Cliente </th>
                 <th class="text-center" style="width: 47%;"> Producto </th>
                 <th class="text-center" style="width: 5%;"> Precio </th>
                 <th class="text-center" style="width: 10%;"> Fecha de entrega </th>
              </tr>
           </thead>
           <tbody>
              <?php foreach ($garantias as $garantia):?>
                 <tr>
                    <td><?php echo remove_junk($garantia['nomCliente']); ?></td>
                    <td><?php echo remove_junk($garantia['nomProducto']); ?></td>
                    <td class="text-right"> <?php echo money_format('%.2n',$garantia['precio']); ?></td>
                    <td class="text-center"><?php echo date("d-m-Y", strtotime ($garantia['fechaEntrega'])); ?></td>
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
