<?php
  $page_title = 'Administración entregas';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
  $user = current_user(); 
  $nivel = $user['user_level'];
  $usuId = $user['id'];
  $vendedores = find_all('users');
  $vendedor = "";
  if (isset($_POST['vendedor'])){  
     $vendedor =  remove_junk($db->escape($_POST['vendedor']));//prueba
  }
?>
<?php include_once('../layouts/header.php'); ?>
<script type="text/javascript" src="../../libs/js/general.js"></script>

<body onload="foco();">
  <form name="form1" method="post" action="entregas.php">
     <br>
     <?php
        $codigo= isset($_POST['Codigo']) ? $_POST['Codigo']:'';
 
        if($vendedor!=""){
           if($codigo!=""){
              if(is_numeric($codigo)){
                 $entregas = entregaCodVen($codigo,$vendedor);
              }else{
                 $entregas = entregaCodVenLike($codigo,$vendedor);
              }
           }else{
              $entregas = entregaVen($vendedor);
           }
        }else{
           if($codigo!=""){
              if(is_numeric($codigo)){
                 $entregas = entregaCod($codigo);
              }else{
                 $entregas = entregaCodLike($codigo);
              }
           }else{
              $entregas = entregas();
           }
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
                        <input type="text" class="form-control" name="Codigo" long="21" oninput="mayusculas(event)">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <select class="form-control" name="vendedor">
                        <option value="">Selecciona un vendedor</option>
                        <?php  foreach ($vendedores as $id): ?>
                        <option value="<?php echo (int)$id['id'] ?>">
                        <?php echo $id['name'] ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>  
                  <a href="#" onclick="entrega();" class="btn btn-primary">Buscar</a>
                  <img src="../../libs/imagenes/Logo.png" height="50" width="50" alt="" align="center">
               </div>   
            </div>   
         </div>
      </div>
      <div class="panel-body">
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th class="text-center" style="width: 10%;"> Vendedor </th>
                  <th class="text-center" style="width: 8%;"> Fecha Registro </th>
                  <th class="text-center" style="width: 7%;"> Hora Registro</th>
                  <th class="text-center" style="width: 42%;"> Producto </th>
                  <th class="text-center" style="width: 5%;"> Cantidad </th>
                  <th class="text-center" style="width: 5%;"> Precio </th>
                  <th class="text-center" style="width: 8%;"> Fecha Entrega </th>
                  <th class="text-center" style="width: 7%;"> Hora Entrega</th>
                  <th class="text-center" style="width: 11%;"> Estatus</th>
                  <th class="text-center" style="width: 3%;"> Acción </th>
               </tr>
            </thead>
            <tbody>
            <?php foreach ($entregas as $entrega):?>
               <?php if ((int)$entrega['idEstatus'] < 3) { ?>
                  <tr>
                     <td> <?php echo remove_junk($entrega['vendedor']); ?></td>
                     <td class="text-center"><?php echo date("d-m-Y", strtotime ($entrega['fechaRegistro'])); ?></td>
                     <td class="text-center"><?php echo date("H:i:s", strtotime ($entrega['horaRegistro'])); ?></td>
                     <td> <?php echo remove_junk($entrega['nomProducto']); ?></td>
                     <td class="text-right"> <?php echo remove_junk($entrega['cantidad']); ?></td>
                     <td class="text-right"> <?php echo remove_junk($entrega['precio']); ?></td>
                     <td class="text-center"><?php echo date("d-m-Y", strtotime ($entrega['fechaEntrega'])); ?></td>
                     <td class="text-center"><?php echo date("H:i:s", strtotime ($entrega['horaEntrega'])); ?></td>
                     <td class="text-right"> <?php echo remove_junk($entrega['estatus']); ?></td>
                     <td class="text-center">
                        <?php if ($usuId == $entrega['idUser'] || $nivel == 1){ ?>
                           <div class="btn-group">
                           <a href="editarEntrega.php?id=<?php echo (int)$entrega['idGrupoEnt'];?>" class="btn btn-success btn-xs" title="Stock" data-toggle="tooltip">
                           <span class="glyphicon glyphicon-pencil"></span>
                           </a>
                           </div>
                        <?php } ?>
                     </td>
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
<?php include_once('../layouts/footer.php'); ?>
