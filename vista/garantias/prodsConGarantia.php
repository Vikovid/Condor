<?php
  $page_title = 'Lista de productos con garantía';
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
  <form name="form1" method="post" action="prodsConGarantia.php">
          <br>
<?php
  $cliente = isset($_POST['cliente']) ? $_POST['cliente']:'';
  $estatus = isset($_POST['estatus']) ? $_POST['estatus']:'';
 
  if($cliente != ""){
     if ($estatus != "") {
        $garantias = garClienteEstatus($cliente,$estatus);
     }else{
        $garantias = garCliente($cliente);
     }
  }else{
     if ($estatus != "")
        $garantias = garEstatus($estatus);
     else
        $garantias = garantias();
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
                  <div class="col-md-3">
                     <select class="form-control" name="estatus">
                        <option value="1">Con garantía</option>
                        <option value="0">Sin garantía</option>
                     </select>
                  </div>  
                  <a href="#" onclick="garantias();" class="btn btn-primary">Buscar</a> 
                  <strong>
                     <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                     <span class="glyphicon glyphicon-th"></span>
                     <span>Productos con garantía</span>
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
