<?php
  $page_title = 'Lista de Clientes';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php include_once('../layouts/header.php'); ?>
<script type="text/javascript" src="../../libs/js/general.js"></script>

<!DOCTYPE html>
<html>
<head>
<title>Lista de Clientes</title>
</head>

<body onload="focoCliente();">
  <form name="form1" method="post" action="clienteApp.php">
          <br>
<?php

  $datoCliente= isset($_POST['cliente']) ? $_POST['cliente']:'';
 
  if($datoCliente!="") {
     if(is_numeric($datoCliente)){
        $cliente = join_cliente_table1a($datoCliente);
     }else{
        $cliente = join_cliente_table2a($datoCliente);
     }
  }else{
     $cliente = join_cliente_table();
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
                  <a href="#" onclick="clienteApp();" class="btn btn-primary">Buscar</a> 
               </div>   
            </div>   
         </div>
      </div>
      <div class="panel-body">
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th class="text-center" style="width: 35%;"> Nombre </th>
                  <th class="text-center" style="width: 50%;"> Dirección </th>
                  <th class="text-center" style="width: 10%;"> Número Telefónico </th>
                  <th class="text-center" style="width: 5%;"> Acciones </th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($cliente as $cliente):?>
               <tr>
                  <td> <?php echo remove_junk($cliente['nom_cliente']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($cliente['dir_cliente']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($cliente['tel_cliente']); ?></td> 
                  <td class="text-center">
                     <div class="btn-group">
                        <a href="registroApp.php?idCredencial=<?php echo (int)$cliente['IdCredencial'];?>" class="btn btn-info btn-xs"  title="Registrar" data-toggle="tooltip">
                        <span class="glyphicon glyphicon-edit"></span>
                        </a>
                     </div>
                     <div class="btn-group">
                        <a href="detalleApp.php?idCredencial=<?php echo (int)$cliente['IdCredencial'];?>" class="btn btn-info btn-xs"  title="Detalle" data-toggle="tooltip">
                        <span class="glyphicon glyphicon-eye-open"></span>
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
</form>
</body>
</html>
<?php include_once('../layouts/footer.php'); ?>
