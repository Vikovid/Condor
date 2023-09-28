<?php
  $page_title = 'Renovar aplicación';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  $user = current_user(); 
  $usuario = $user['id'];
  $idSucursal = $user['idSucursal'];
  ini_set('date.timezone','America/Mexico_City');
  $fecha_actual=date('Y-m-d',time());
  $hora_actual=date('H:i:s',time());
  
  $idAplicacion = isset($_GET['idApp']) ? $_GET['idApp']:'';

  $respAplic = buscaDatosAplicRenovar($idAplicacion);

  $pagoPeriodo = $respAplic['pagoPeriodo'];
  $nomPeriodo = $respAplic['nombre'];
  $fechaRenovacion = $respAplic['fechaRenovacion'];
  $tipoPago = $respAplic['tipoPago'];
  $estatus = $respAplic['activo'];
  $periodo = $respAplic['periodo'];

  $tipo_pago = buscaRegistroPorCampo('tipo_pago','id_pago',$tipoPago);
  $tipos_pago = find_all('tipo_pago');
  $all_periodos = find_all('periodo');

  if(!$respAplic){
     $session->msg("d","Missing aplicación id.");
     redirect('appsActivas.php');
  }

  if ($respAplic['pagoPeriodo'] == "0.00")
     $fechaIniPeriodo = date("Y-m-d", strtotime ($respAplic['fechaInicio']));
  else
     $fechaIniPeriodo = date("Y-m-d", strtotime ($respAplic['fechaRenovacion']));

  $diaRen = date("d", strtotime ($fechaIniPeriodo));
  $mesRen = date("m", strtotime ($fechaIniPeriodo));
  $anioRen = date("Y", strtotime ($fechaIniPeriodo)); 

  $fechaFinPeriodo = date("Y-m-d", mktime(0,0,0, $mesRen+$respAplic['periodo'],$diaRen,$anioRen));

  if ($pagoPeriodo == "0.00" && $fechaRenovacion == $respAplic['fechaInicio'])
     $pagoDelPeriodo = $respAplic['pagoPeriodoAcord'];
  else
     $pagoDelPeriodo = $pagoPeriodo;

  if(isset($_POST['renovar'])){
     $req_fields = array('pagoPeriodo','periodo','fechaRen','tipoPago','estatus');
     validate_fields($req_fields);

     if(empty($errors)){
        $a_pagoPeriodo = remove_junk($db->escape($_POST['pagoPeriodo']));
        $a_periodo = remove_junk($db->escape($_POST['periodo']));
        $a_fechaRen = remove_junk($db->escape($_POST['fechaRen']));
        $a_tipoPago = remove_junk($db->escape($_POST['tipoPago']));
        $a_estatus = remove_junk($db->escape($_POST['estatus']));

        actEstadoAplicacion($a_estatus,$respAplic['idAplicacion']);

        $respPagosApp = regPagoAplicacion($idAplicacion,$a_pagoPeriodo,$a_periodo,$a_fechaRen,$a_tipoPago);

        if ($respPagosApp){
           if ($a_tipoPago == 1){
              $consMonto = buscaRegistroMaximo("caja","id");
              $montoActual=$consMonto['monto'];
              $idCaja = $consMonto['id'];

              $montoFinal = $montoActual + $a_pagoPeriodo;

              $respCaja = actCaja($montoFinal,$fecha_actual,$idCaja);
 
              if ($respCaja){
                 registrarEfectivo('15',$montoActual,$montoFinal,$idSucursal,$usuario,'',$fecha_actual,$hora_actual);
              }
           }
           $session->msg('s',"La aplicación se ha actualizado. ");
           redirect('appsActivas.php', false);
        }else{
           $session->msg('d',' Lo siento, falló la actualización.');
           redirect('renovarApp.php?idApp='.$respAplic['idAplicacion'], false);
        }
     //aqui esta ok
     }else{
        $session->msg("d", $errors);
        redirect('renovarApp.php?idApp='.$respAplic['idAplicacion'], false);
     }
  }
?>
<?php include_once('../layouts/header.php'); ?>
<script type="text/javascript" src="../../libs/js/general.js"></script>

<body onload="estatus();">
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
               <span>Renovar aplicación:</span>
               <span><?php echo $respAplic['nomInstancia'] ?></span>
               <span><?php echo "  "; ?></span>
               <span><?php echo $respAplic['tipo'] ?></span>
            </strong>
         </div>
         <form name="form1" method="post" action="renovarApp.php?idApp=<?php echo (int)$respAplic['idAplicacion'] ?>">

         <div class="form-group row">
            <label class="col-sm-4 col-form-label">Pago del período:</label>
            <div class="col-sm-2">
               <input type="number" step="0.01" class="form-control" name="pagoPeriodo" value="<?php echo remove_junk($pagoDelPeriodo);?>">
            </div>
         </div>
         <div class="form-group row">
            <label class="col-sm-4 col-form-label">Período:</label>
            <div class="col-sm-3">
               <select class="form-control" name="periodo" onchange="fechaRenovacion();">
                  <option value="">Selecciona un período</option>
                  <?php foreach ($all_periodos as $periodo): ?>
                     <option value="<?php echo $periodo['periodo']; ?>" <?php if($respAplic['periodo'] === $periodo['periodo']): echo "selected"; endif; ?> >
                  <?php echo remove_junk($periodo['nombre']); ?></option>
                  <?php endforeach; ?>
               </select>
            </div>
         </div>  
         <div class="form-group row">
            <label class="col-sm-4 col-form-label">Fecha de inicio de período:</label>
            <div class="col-sm-3">
               <input type="date" name="fechaRen" min="<?php echo $fechaFinPeriodo ?>" value="<?php echo remove_junk($fechaFinPeriodo); ?>">
            </div>
         </div>
         <div class="form-group row">
            <label class="col-sm-4 col-form-label">Forma de pago:</label>
            <div class="col-sm-3">
               <select class="form-control" name="tipoPago">
               <option value="">Selecciona una forma de pago</option>
               <?php  foreach ($tipos_pago as $tipo): ?>
               <option value="<?php echo $tipo['id_pago']; ?>" <?php if($respAplic['tipoPago'] === $tipo['id_pago']): echo "selected"; endif; ?> >
               <?php echo remove_junk($tipo['tipo_pago']); ?></option>
               <?php endforeach; ?>
               </select>
            </div>
         </div>  
         <div class="form-group row">
            <label class="col-sm-4 col-form-label">Estatus:</label>
            <div class="col-sm-3">
               <select class="form-control" name="estatus">
               <option value="">Selecciona el estatus</option>
               <option value="1">Activa</option>
               <option value="0">No activa</option>
               </select>
            </div>
         </div>
         <input type="hidden" name="fechaAux" value="<?php echo $fechaIniPeriodo ?>">
         <input type="hidden" name="estatusAux" value="<?php echo $estatus ?>">
         <button type="submit" name="renovar" class="btn btn-danger">Renovar</button>
         </form>
      </div>         
   </div>
</div>
<?php include_once('../layouts/footer.php'); ?>
