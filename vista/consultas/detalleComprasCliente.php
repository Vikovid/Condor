<?php
   $page_title = 'Lista de productos comprados por el cliente';
   require_once('../../modelo/load.php');

   // Checkin What level user has permission to view this page
   page_require_level(3);

   $idCliente =  $_GET['idCliente'];
   $nomCliente = "";

   if(isset($_GET['idCliente'])){
      $productos =  buscaComprasCliente($idCliente);
      $cliente =    buscaRegistroPorCampo('cliente','idcredencial',$idCliente);
      $nomCliente = $cliente['nom_cliente'];
   }
?>

<?php include_once('../layouts/header.php'); ?>

<!DOCTYPE html>
<html>
<head>
<title>Lista de productos comprados por el cliente</title>
</head>
<body onload="sucursal();">
<form name="form1" method="post" action="detalleComprasCliente.php">
   <div class="row col-md-12">
      <?php echo display_msg($msg); ?>
   </div>
   <div class="row col-md-12">
      <div class="panel panel-default">
         <div class="panel-heading clearfix">
            <div class="pull-right">
               <div class="form-group">
                  <div class="col-md-4">
                     <div class="input-group">
                        <span class="input-group-addon">
                           <h4>Productos comprados por: <?php echo $nomCliente; ?></h4>
                        </span>
                     </div>
                  </div>
               </div>   
            </div>   
         </div>
         <div class="panel-body">
            <table class="table table-bordered">
               <thead>
                  <tr>
                     <th class="text-center" style="width: 3%;">#</th>
                     <th class="text-center" style="width: 47%;">Producto</th>
                     <th class="text-center" style="width: 10%;">Cantidad</th>
                     <th class="text-center" style="width: 10%;">Fecha última venta</th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($productos as $producto):?>
                  <tr>
                     <td class="text-center"><?php echo count_id();?></td>
                     <td> <?php echo remove_junk(utf8_encode($producto['name'])); ?></td>
                     <td class="text-center"><?php echo '$'.money_format('%.2n',$producto['qty']); ?></td>
                     <td class="text-center"><?php echo $producto['date'] ?></td>
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