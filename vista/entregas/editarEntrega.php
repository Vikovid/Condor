<?php
  $page_title = 'Editar entrega';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);

  ini_set('date.timezone','America/Mexico_City');
  $fecha_actual=date('Y-m-d',time());

  $vendedores = find_all_user();
  $estados = find_all("estatusent");
  $idGrupoEnt = isset($_GET['id']) ? $_GET['id']:'';

  $entregas = entregasGrupo($idGrupoEnt);
  $datosEntrega = entregasGrupo($idGrupoEnt);

  foreach ($datosEntrega as $dato):
     $nomVendedor = $dato['vendedor'];
     $fechaEntrega = $dato['fechaEntrega'];
     $horaEntrega = $dato['horaEntrega'];
     $estatus = $dato['idEstatus'];

     break;
  endforeach;  

  if(!$entregas){
     $session->msg("d","Missing entrega idGrupoEnt.");
     redirect('entregas.php');
  }

  if(isset($_POST['entrega'])){
     $id  = remove_junk($db->escape($_POST['idGrupoEnt']));
     $req_fields = array('fecha','hora','estatus');
     validate_fields($req_fields);
     if(empty($errors)){

        $fechaEntrega = remove_junk($db->escape($_POST['fecha']));
        $horaEntrega = remove_junk($db->escape($_POST['hora']));
        $estatus = remove_junk($db->escape($_POST['estatus']));

        $horaEntrega = date("H:i:s", strtotime ($horaEntrega));

        $respuesta = actEntrega($fechaEntrega,$horaEntrega,$estatus,$id);
 
        if($respuesta){
           $session->msg('s',"Registro Exitoso.");
           redirect('entregas.php', false);
        }else{
           $session->msg('d','Lo siento, falló el registro.');
           redirect('editarEntrega.php?id='.$id, false);
        }
     }else{
        $session->msg("d", $errors);
        redirect('editarEntrega.php?id='.$id,false);
     }
  }
?>
<?php include_once('../layouts/header.php'); ?>
<script type="text/javascript" src="../../libs/js/general.js"></script>

<body onload="horasEdicion();">
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
   <div class="col-md-11">
      <div class="panel panel-default">
         <div class="panel-heading">
            <strong>
               <span class="glyphicon glyphicon-th"></span>
               <span>Editar entregas de :</span>
               <span><?php echo $nomVendedor ?></span>            
            </strong>
         </div>
         <div class="panel-body">
            <div class="col-md-10">
               <form name="form1" method="post" action="editarEntrega.php?id=<?php echo (int)$idGrupoEnt ?>">
               <div class="col-md-11">
                  <div class="panel-body">
                     <table class="table table-bordered">
                        <tbody>
                        <thead>
                        <tr>
                           <th class="text-center"> Producto </th>
                           <th class="text-center"> Cantidad </th>
                           <th class="text-center"> Precio </th>
                           <th class="text-center"> Acción </th>
                        </tr>
                        </thead>
                        <?php foreach ($entregas as $entrega): ?>
                           <tr>
                              <td> <?php echo remove_junk($entrega['nomProducto']); ?></td>
                              <td> <?php echo remove_junk($entrega['cantidad']); ?></td>
                              <td class="text-right"> <?php echo money_format('%.2n',$entrega['precio']); ?></td>
                              <td class="text-center">
                                 <div class="btn-group">
                                    <a href="delProdEnt.php?id=<?php echo (int)$entrega['id_entrega'];?>" class="btn btn-danger btn-xs"  title="Eliminar" data-toggle="tooltip">
                                       <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                 </div>
                              </td>
                           </tr>
                        <?php endforeach; ?>
                        </tbody>
                     </table>
                  </div>
                  <div class="form-group">
                     <div class="row">                
                        <div class="col-md-4">     
                           <div class="form-group">                     
                              <label for="qty">Fecha de entrega</label>
                              <div class="input-group">
                                 <div class="col-sm-3">
                                    <input type="date" name="fecha" min="<?php echo remove_junk($fecha_actual);?>" onchange="horasEdicion();" value="<?php echo remove_junk($fechaEntrega);?>">
                                 </div>
                              </div>
                           </div>              
                        </div>
                        <div class="col-md-3">     
                           <div class="form-group">                     
                              <label for="qty">Hora de entrega</label>
                              <div class="input-group">
                                 <select class="form-control" id="horasLista" name="hora">
                                 </select>                
                              </div>
                           </div>              
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="qty">Estatus</label>
                              <div class="input-group">
                                 <select class="form-control" name="estatus">
                                 <?php  foreach ($estados as $estado): ?>
                                 <option value="<?php echo (int)$estado['id_estatus'];?>" <?php if($estatus === $estado['id_estatus']): echo "selected"; endif; ?> >
                                 <?php echo $estado['estatus'] ?></option>
                                 <?php endforeach; ?>
                                 </select>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <input type="hidden" name="idGrupoEnt" value="<?php echo $idGrupoEnt ?>">
               <input type="hidden" name="horaAux" value="<?php echo $horaEntrega ?>">
               <button type="submit" name="entrega" class="btn btn-danger">Actualizar</button>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
</body>
<?php include_once('../layouts/footer.php'); ?>
