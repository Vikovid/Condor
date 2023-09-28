<?php
  $page_title = 'Registro garantia';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  ini_set('date.timezone','America/Mexico_City');  
  $fecha_actual=date('Y-m-d',time());

  if(isset($_POST['registro'])){
     $req_fields = array('nomCliente','nomProducto','precio');
     validate_fields($req_fields);

     if(empty($errors)){
        $r_nomCliente = remove_junk($db->escape($_POST['nomCliente']));
        $r_nomProducto = remove_junk($db->escape($_POST['nomProducto']));
        $r_precio = remove_junk($db->escape($_POST['precio']));

        $respuesta = altaGarantia($r_nomCliente,$r_nomProducto,$r_precio,$fecha_actual,'1');

        if($respuesta){
           $session->msg('s',"Registro agregado exitosamente. ");
           redirect('prodsConGarantia.php', false);
        }else{
           $session->msg('d','Lo siento, Falló el registro.');
           redirect('registroGarantia.php', false);
        }
     }else{
        $session->msg("d", $errors);
        redirect('registroGarantia.php', false);
     }
  }
?>
<?php include_once('../layouts/header.php'); ?>
<script type="text/javascript" src="../../libs/js/general.js"></script>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
   <div class="col-md-9">
      <div class="panel panel-default">
         <div class="panel-heading">
            <strong>
               <span class="glyphicon glyphicon-th"></span>
               <span>Registro de garantía</span>
               <img src="../../libs/imagenes/Logo.png" height="50" width="50" alt="" align="center">
            </strong>
         </div>
         <div class="panel-body">
            <div class="col-md-12">
            <form method="post" action="registroGarantia.php" class="clearfix">
               <div class="form-group">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-th-large"></i>
                           </span>
                           <input type="text" class="form-control" name="nomCliente" placeholder="Nombre del cliente">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-th-large"></i>
                           </span>
                           <input type="text" class="form-control" name="nomProducto" placeholder="Nombre del producto" oninput="mayusculas(event)">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col-md-4">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-usd"></i>
                           </span>
                           <input type="number" step="0.01" class="form-control" name="precio" placeholder="Precio">
                        </div>
                     </div>
                  </div>
               </div>
               <button type="submit" name="registro" class="btn btn-danger">Registrar</button>
            </form>
            </div>
         </div>
      </div>
   </div>
</div>
<?php include_once('../layouts/footer.php'); ?>
