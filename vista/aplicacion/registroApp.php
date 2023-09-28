<?php
  $page_title = 'Registro aplicación';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  $all_tipo_pagos = find_all('tipo_pago');
  $all_periodos = find_all('periodo');
  $idCredencial= $_GET['idCredencial'];
  $cliente = buscaRegistroPorCampo('cliente','idCredencial',$idCredencial);
  $nomCliente = $cliente['nom_cliente'];

  $user = current_user(); 
  $usuario = $user['id'];
  $idSucursal = $user['idSucursal'];
  ini_set('date.timezone','America/Mexico_City');
  $hora_actual=date('H:i:s',time());
  $fecha_actual=date('Y-m-d',time());

  if(isset($_POST['registro'])){
     $credencial = remove_junk($db->escape($_POST['credencial']));
     $req_fields = array('nombre','nomBaseDatos','tipo','pagoIni','fecha','pagoPeriodo','periodo','tipoPago');
     validate_fields($req_fields);

     if(empty($errors)){
        $r_nombre = remove_junk($db->escape($_POST['nombre']));
        $r_nomBaseDatos = remove_junk($db->escape($_POST['nomBaseDatos']));
        $r_tipo = remove_junk($db->escape($_POST['tipo']));
        $r_pagoIni = remove_junk($db->escape($_POST['pagoIni']));     
        $r_fecha = remove_junk($db->escape($_POST['fecha']));
        $r_pagoPeriodo = remove_junk($db->escape($_POST['pagoPeriodo']));
        $r_periodo = remove_junk($db->escape($_POST['periodo']));
        $r_tipoPago = remove_junk($db->escape($_POST['tipoPago']));
        $nomInstancia = "";

        $respAplic = nombreAplicacion($r_nombre,$credencial);
 
        if($respAplic != null)
           $nomInstancia = $respAplic['nomInstancia'];

        if($nomInstancia == ""){
           $resultado = altaAplicacion($credencial,$r_nombre,$r_nomBaseDatos,$r_tipo,$r_pagoIni,$r_fecha,$r_pagoPeriodo,'1','1');

           if($resultado){
              $respIdAplic = buscaRegistroMaximo("aplicacion","idAplicacion");          

           if($respIdAplic != null)
              $idAplicacion = $respIdAplic['idAplicacion'];

              $respRegPAgo = regPagoAplicacion($idAplicacion,'0',$r_periodo,$r_fecha,$r_tipoPago);

              if($r_tipoPago == 1){
                 $consMonto = buscaRegistroMaximo("caja","id");
                 $montoActual=$consMonto['monto'];
                 $idCaja = $consMonto['id'];

                 $montoFinal = $montoActual + $r_pagoIni;

                 $respCaja = actCaja($montoFinal,$fecha_actual,$idCaja);

                 if($respCaja){
                    registrarEfectivo('15',$montoActual,$montoFinal,$idSucursal,$usuario,'',$fecha_actual,$hora_actual);
                 }
              }
              $session->msg('s',"Registro agregado exitosamente. ");
              redirect('clienteApp.php', false);
           }else{
              $session->msg('d','Lo siento, Falló el registro.');
              redirect('registroApp.php?idCredencial='.$credencial, false);
           }
        }else{
           $session->msg('d','Instancia registrada para ese cliente');
           redirect('registroApp.php?idCredencial='.$credencial, false);
        }
     }else{
        $session->msg("d", $errors);
        redirect('registroApp.php?idCredencial='.$credencial, false);
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
   <div class="col-md-9">
      <div class="panel panel-default">
         <div class="panel-heading">
            <strong>
               <span class="glyphicon glyphicon-th"></span>
               <span>Registro de aplicación para: <?php echo $nomCliente; ?></span>
                  <img src="../imagenes/Logo.png" height="50" width="50" alt="" align="center">
            </strong>
         </div>
         <div class="panel-body">
            <div class="col-md-12">
               <form method="post" action="registroApp.php?idCredencial=<?php echo (int)$cliente['idcredencial'] ?>" class="clearfix">
               <div class="form-group">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-th-large"></i>
                           </span>
                           <input type="text" class="form-control" name="nombre" placeholder="Nombre instancia">
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
                           <input type="text" class="form-control" name="nomBaseDatos" placeholder="Nombre de la base de datos">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <select class="form-control" name="tipo">
                     <option value="">Selecciona el tipo de aplicación</option>
                     <option value="Condor">Condor</option>
                     <option value="Balam">Balam</option>
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col-md-4">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-usd"></i>
                           </span>
                           <input type="number" step="0.01" class="form-control" name="pagoIni" placeholder="Pago inicial">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col-md-4">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-calendar"></i>
                           </span>
                           Fecha de inicio
                           <input type="date" class="form-control" name="fecha" min="<?php echo $fecha_actual ?>">
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
                           <input type="number" step="0.01" class="form-control" name="pagoPeriodo" placeholder="Pago del período">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <select class="form-control" name="periodo">
                     <option value="">Selecciona un período</option>
                     <?php  foreach ($all_periodos as $idPeriodo): ?>
                     <option value="<?php echo (int)$idPeriodo['periodo'] ?>">
                     <?php echo $idPeriodo['nombre'] ?></option>
                     <?php endforeach; ?>
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <select class="form-control" name="tipoPago">
                     <option value="">Selecciona Forma de Pago</option>
                     <?php  foreach ($all_tipo_pagos as $id_pago): ?>
                     <option value="<?php echo (int)$id_pago['id_pago'] ?>">
                     <?php echo $id_pago['tipo_pago'] ?></option>
                     <?php endforeach; ?>
                     </select>
                  </div>
               </div>
               <input type="hidden" value="<?php echo $idCredencial ?>" name="credencial">
               <button type="submit" name="registro" class="btn btn-danger">Registrar</button>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<?php include_once('../layouts/footer.php'); ?>
