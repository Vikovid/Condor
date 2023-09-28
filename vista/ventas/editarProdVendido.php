<?php
  $page_title = 'Editar producto especial';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);

  $producto = buscaRegistroPorCampo("prodsvendidos","idProdVendido",$_GET['idProducto']);
  if(!$producto){
     $session->msg("d","Missing proveedor idProdVendido.");
     redirect('productosVendidos.php');
  }

  if(isset($_POST['productoVendido'])){
     $req_fields = array('nomProducto');
     validate_fields($req_fields);

     if(empty($errors)){
        $nomProducto = remove_junk($db->escape($_POST['nomProducto']));
        $marca = remove_junk($db->escape($_POST['marca']));
        $modelo = remove_junk($db->escape($_POST['modelo']));
        $codigo = remove_junk($db->escape($_POST['codigo']));
        $numSerie = remove_junk($db->escape($_POST['numSerie']));
        $nota = remove_junk($db->escape($_POST['nota']));

        $resultado = actProdVendido($nomProducto,$marca,$modelo,$codigo,$numSerie,$nota,$producto['idProdVendido']);

        if($resultado){
           $session->msg('s',"El producto ha sido actualizado.");
           redirect('productosVendidos.php?idProducto='.$producto['idProdVendido'], false);
        }else{
           $session->msg('d','Lo siento, falló la actualización.');
           redirect('editarProdVendido.php?idProducto='.$producto['idProdVendido'], false);
        }
     }else{
        $session->msg("d", $errors);
        redirect('editarProdVendido.php?idProducto='.$producto['idProdVendido'], false);
     }
  }
?>
<?php include_once('../layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
      <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
   <div class="panel panel-default">
      <div class="panel-heading">
         <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Editar producto especial</span>
         </strong>
      </div>
      <div class="panel-body">
         <div class="col-md-7">
            <form method="post" action="editarProdVendido.php?idProducto=<?php echo (int)$producto['idProdVendido'] ?>">
               <div class="form-group">
                  <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-th-large"></i>
                     </span>
                     <input type="text" class="form-control" name="nomProducto" value="<?php echo remove_junk($producto['nomProducto']);?>">
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-th-large"></i>
                     </span>
                     <input type="text" class="form-control" name="marca" value="<?php echo remove_junk($producto['marca']);?>">
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-th-large"></i>
                     </span>
                     <input type="text" class="form-control" name="modelo" value="<?php echo remove_junk($producto['modelo']);?>">
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-th-large"></i>
                     </span>
                     <input type="text" class="form-control" name="codigo" value="<?php echo remove_junk($producto['codigo']);?>">
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-th-large"></i>
                     </span>
                     <input type="text" class="form-control" name="numSerie" value="<?php echo remove_junk($producto['numSerie']);?>">
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-th-large"></i>
                     </span>
                     <textarea name="nota" class="form-control" placeholder="Nota" maxlength="200" rows="2" style="resize: none"><?php echo remove_junk($producto['nota']); ?></textarea>
                  </div>
               </div>
               <button type="submit" name="productoVendido" class="btn btn-danger">Actualizar</button>
            </form>
         </div>
      </div>
   </div>
</div>
<?php include_once('../layouts/footer.php'); ?>
