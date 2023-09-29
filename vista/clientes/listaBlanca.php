<?php
   $page_title = 'Clientes en lista blanca';
   require_once('../../modelo/load.php');

   // Checkin What level user has permission to view this page
   page_require_level(3);

   $user = current_user(); 
   $nivel = $user['user_level'];

   $cliente = join_cliente_table();

   $codigo = (isset($_POST['Codigo']) && $_POST['Codigo']!='') ? $_POST['Codigo']:'';
 
   if ($codigo!="") {
      if (is_numeric($codigo))
         $cliente = join_cliente_table1a($codigo);
      else
        $cliente = aliasCliente($codigo);
   }
?>

<?php include_once('../layouts/header.php'); ?>

<script type="text/javascript" src="../../libs/js/general.js"></script>

<!DOCTYPE html>
<html>
<head>
<title>Lista de Clientes</title>
</head>

<body onload="foco();">
<form name="form1" method="post" action="listaBlanca.php">
   <div class="row col-md-8">
      <?php echo display_msg($msg); ?>
   </div>
   <div class="row col-md-8">
      <div class="panel panel-default">
         <div class="panel-heading clearfix">
            <div class="pull-right">
               <div class="form-group">
                  <div class="col-md-4">
                     <div class="input-group">
                        <span class="input-group-addon">
                           <i class="glyphicon glyphicon-user"></i>
                        </span>
                        <input type="text" class="form-control" name="Codigo" long="21">
                     </div>
                  </div>  
                  <a href="#" onclick="listaBlanca();" class="btn btn-primary">Buscar</a> 
                  <div class="pull-right">
                     <strong>
                        <span class="glyphicon glyphicon-th"></span>
                        <span>Lista blanca</span>
                     </strong>
                  </div>
               </div>   
            </div>   
         </div>
         <div class="panel-body">
            <table class="table table-bordered">
               <thead>
                  <tr>
                     <th class="text-center" style="width: 3%;">#</th>
                     <th class="text-center" style="width: 30%;"> Nombre </th>
                     <th class="text-center" style="width: 10%;"> Alias </th>
                     <th class="text-center" style="width: 7%;"> Id Cliente </th>
                     <?php if ($nivel == "1" || $nivel == "2"){ ?>
                        <th class="text-center" style="width: 5%;"> Acciones </th>
                     <?php } ?>
                     <?php if ($nivel == "3"){ ?>
                        <th class="text-center" style="width: 5%;"> Acción </th>
                     <?php } ?>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($cliente as $cliente):
                           if($cliente['lista'] == "1") { ?>
                     <tr>
                        <td class="text-center"><?php echo count_id();?></td>
                        <td><?php echo remove_junk($cliente['nom_cliente']); ?></td>
                        <td><?php echo remove_junk($cliente['alias']); ?></td>
                        <td class="text-center"><?php echo remove_junk($cliente['IdCredencial']); ?></td>
                        <td class="text-center">
                           <div class="btn-group">
                              <?php if ($nivel == "1" || $nivel == "2"){ ?>
                                 <a href="editarListaCliente.php?IdCredencial=<?php echo (int)$cliente['IdCredencial'];?>" class="btn btn-info btn-xs" title="Editar" data-toggle="tooltip">
                                 <span class="glyphicon glyphicon-edit"></span>
                                 </a>
                              <?php } ?>   
                              <a href="../consultas/detalleComprasCliente.php?idCliente=<?php echo (int)$cliente['IdCredencial'];?>" class="btn btn-success btn-xs" title="Detalle" data-toggle="tooltip">
                              <span class="glyphicon glyphicon-list-alt"></span>
                              </a>
                           </div>
                        </td>
                     </tr>
                  <?php } endforeach; ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</form>
</body>
</html>
<?php include_once('../layouts/footer.php'); ?>