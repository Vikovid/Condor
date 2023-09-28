<?php
   $page_title = 'Editar lista del cliente';
   require_once('../../modelo/load.php');
   // Checkin What level user has permission to view this page
   page_require_level(2);

   ini_set('date.timezone','America/Mexico_City');
   $fecha_actual=date('Y-m-d',time());

   $idCliente = $_GET['IdCredencial'];

   if(isset($_GET['IdCredencial']))
      $cliente = buscaRegistroPorCampo("cliente","idcredencial",$idCliente);

   $listaAux = $cliente['lista'];

   if(isset($_POST['ActCliente'])){
      $req_fields = array('lista');
      validate_fields($req_fields);
      if(empty($errors)){
         $c_lista = remove_junk($db->escape($_POST['lista']));

         $resultado = actRegistroPorCampo('cliente','lista',$c_lista,'idcredencial',$idCliente);

         if($resultado){
            $session->msg('s',"El cliente ha sido actualizado.");
            if ($c_lista == "0")
               redirect('listaNegra.php', false);
            else
               redirect('listaBlanca.php',false);
         }else{
            $session->msg('d','Lo siento, falló el registro.');
            redirect('editarListaCliente.php', false);
         }
      }else{
         $session->msg("d", $errors);
         redirect('editarListaCliente.php',false);
      }
   }
?>
<?php include_once('../layouts/header.php'); ?>
<script type="text/javascript" src="../../libs/js/general.js"></script>

<!DOCTYPE html>
<html>
<head>
<title>Edición de clientes</title>
</head>

<body onload="listaAnt();">
<div class="row">
   <div class="col-md-12">
      <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
   <div class="col-md-7">
      <div class="panel panel-default">
         <div class="panel-heading">
            <strong>
               <span class="glyphicon glyphicon-th"></span>
               <span>Editar lista del cliente</span>   
            </strong>
          	<img src="../../libs/imagenes/Logo.png" height="50" width="50" alt="" align="center">
         </div>
         <div class="panel-body">
            <div class="col-md-12">
            <form method="post" name="form1" action="editarListaCliente.php?IdCredencial=<?php echo $_GET['IdCredencial'];?>">
            <table>
               <tr>
                  <td>
                     <div class="form-group">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-user"></i>
                           </span>
                           <input type="text" class="form-control" name="nom_cliente" value="<?php echo $cliente['nom_cliente']; ?>" readonly>
                        </div>
                     </div>
                  </td>
               </tr>
               <tr>
                  <td>
                     <div class="form-group">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-user"></i>
                           </span>
                           <input type="text" class="form-control" name="alias" value="<?php echo $cliente['alias']; ?>" readonly>
                        </div>
                     </div>
                  </td>
               </tr>
               <tr>
                  <td>   
                     <div class="form-group">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-signpost-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path d="M7 1.414V4h2V1.414a1 1 0 0 0-2 0zM1 5a1 1 0 0 1 1-1h10.532a1 1 0 0 1 .768.36l1.933 2.32a.5.5 0 0 1 0 .64L13.3 9.64a1 1 0 0 1-.768.36H2a1 1 0 0 1-1-1V5zm6 5h2v6H7v-6z"/>
                              </svg></i>
                           </span>
                           <input type="text" class="form-control" name="direccion" value="<?php echo $cliente['dir_cliente']; ?>" readonly>
                        </div>
                     </div>
                  </td>
               </tr>   
               <tr>
                  <td>
                     <div class="form-group">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-earphone"></i>
                           </span>
                          <input type="number" class="form-control" name="telefono" value="<?php echo $cliente['tel_cliente']; ?>" readonly>
                        </div>
                     </div>
                  </td>
               </tr> 
               <tr>
                  <td> 
                     <div class="form-group">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-envelope"></i>
                           </span>
                           <input type="text" class="form-control" name="correo" value="<?php echo $cliente['correo']; ?>" readonly>
                        </div>
                     </div>
                  </td>
               </tr>
               <tr>
                  <td width="10%">
                     <div class="form-group">                     
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-th-list"></i>
                           </span>
                           <select class="form-control" name="lista">
                              <option value="1">Blanca</option>
                              <option value="0">Negra</option>
                           </select>
                        </div>               
                     </div>
                  </td>
               </tr>
            </table>
            <div class="col text-center">
               <input type="hidden" name="listaAux" value="<?php echo $listaAux ?>">
               <button type="submit" name="ActCliente" class="btn btn-danger">Actualizar</button>
            </div>
         </form>
      </div>
   </div>
</div>
<?php include_once('../layouts/footer.php'); ?>
