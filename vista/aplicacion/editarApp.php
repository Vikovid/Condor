<?php
  $page_title = 'Editar aplicación';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  $user = current_user(); 
  $usuario = $user['id'];
  $idSucursal = $user['idSucursal'];
  ini_set('date.timezone','America/Mexico_City');
  $fecha_actual=date('Y-m-d',time());
  $hora_actual=date('H:i:s',time());
?>
<?php
$idAplicacion = isset($_GET['idApp']) ? $_GET['idApp']:'';

$respAplic = buscaDatosAplicacion($idAplicacion);

$respNumPagos = cuentaRegistros('idPagosAplic','pagosaplicacion','idAplicacion',$idAplicacion);

$numPagos = $respNumPagos['total'];
$pagoPeriodo = $respAplic['pagoPeriodo'];
$nomPeriodo = $respAplic['nombre'];
$fechaRenovacion = $respAplic['fechaRenovacion'];
$tipoPago = $respAplic['tipoPago'];
$nomInstancia = $respAplic['nomInstancia'];
$nomBaseDatos = $respAplic['nomBaseDatos'];
$tipo = $respAplic['tipo'];
$pagoInicial = $respAplic['pagoInicial'];
$fechaInicio = $respAplic['fechaInicio'];

if ($numPagos > 1){
   $pagoDelPeriodo = $pagoPeriodo;
   $fechaIniPeriodo = $fechaRenovacion;
}else{
   $pagoDelPeriodo = $respAplic['pagoPeriodoAcord'];
   $fechaIniPeriodo = $fechaInicio;
}

$tipo_pago = buscaRegistroPorCampo('tipo_pago','id_pago',$tipoPago);
$tipos_pago = find_all('tipo_pago');
$all_periodos = find_all('periodo');

if(!$respAplic){
  $session->msg("d","Missing aplicación id.");
  redirect('appsActivas.php');
}
?>
<?php
 if(isset($_POST['editar'])){
    $pagoInicialOrig = remove_junk($db->escape($_POST['pagoInicialAux']));
    $pagoPeriodoOrig = remove_junk($db->escape($_POST['pagoPeriodoAux']));

    if ($numPagos > 1)
       $req_fields = array('pagoPeriodo','periodo','fechaInicio','tipoPago');
    else
       $req_fields = array('pagoPeriodo','periodo','fechaInicio','tipoPago','nomInstancia','nomBaseDatos','tipo','pagoInicial');

    validate_fields($req_fields);

    if(empty($errors)){
       $a_pagoPeriodo = remove_junk($db->escape($_POST['pagoPeriodo']));
       $a_periodo = remove_junk($db->escape($_POST['periodo']));
       $a_fechaInicio = remove_junk($db->escape($_POST['fechaInicio']));
       $a_tipoPago = remove_junk($db->escape($_POST['tipoPago']));

       if ($numPagos == 1){
          $a_nomInstancia = remove_junk($db->escape($_POST['nomInstancia']));
          $a_nomBaseDatos = remove_junk($db->escape($_POST['nomBaseDatos']));
          $a_tipo = remove_junk($db->escape($_POST['tipo']));
          $a_pagoInicial = remove_junk($db->escape($_POST['pagoInicial']));

          actAplicacion($a_pagoPeriodo,$a_fechaInicio,$a_nomInstancia,$a_nomBaseDatos,$a_pagoInicial,$a_tipo,$respAplic['idAplicacion']);
       }

       $respPagosAplic = actPagosAplicacion($a_periodo,$a_fechaInicio,$a_tipoPago,$a_pagoPeriodo,$respAplic['idPagosAplic']);

       if ($respPagosAplic){
          if ($a_tipoPago == 1){
             $consMonto = buscaRegistroMaximo("caja","id");
             $montoActual=$consMonto['monto'];
             $idCaja = $consMonto['id'];

             if ($numPagos > 1)
                $montoInicial = $pagoPeriodoOrig - $a_pagoPeriodo;
             else
                $montoInicial = $pagoInicialOrig - $a_pagoInicial;

             if ($montoInicial >= 0)
                $montoFinal = $montoActual - $montoInicial;
             else
                $montoFinal = $montoActual + ($montoInicial * -1);

             $respCaja = actCaja($montoFinal,$fecha_actual,$idCaja); 

             if ($respCaja && $montoInicial > 0){
                registrarEfectivo('18',$montoActual,$montoFinal,$idSucursal,$usuario,'',$fecha_actual,$hora_actual);                
             }

             if ($respCaja && $montoInicial < 0){
                registrarEfectivo('17',$montoActual,$montoFinal,$idSucursal,$usuario,'',$fecha_actual,$hora_actual);                
             }
          }

          $session->msg('s',"Se ha actualizado la aplicación");
          redirect('appsActivas.php', false);
       }else{
          $session->msg('d',' Lo siento, falló la actualización.');
          redirect('editarApp.php?idApp='.$respAplic['idAplicacion'], false);
       }
    //aqui esta ok
    }else{
       $session->msg("d", $errors);
       redirect('editarApp.php?idApp='.$respAplic['idAplicacion'], false);
    }
}
?>
<?php include_once('../layouts/header.php'); ?>

<script language="Javascript">

function datosListas(){
  document.form1.tipo.value = document.form1.tipoAux.value;
}

</script>  

<body onload="datosListas();">
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
            <span>Editar aplicación:</span>
            <span><?php echo $nomInstancia ?></span>
            <span><?php echo "  "; ?></span>
            <span><?php echo $tipo ?></span>
         </strong>
        </div>
        <form name="form1" method="post" action="editarApp.php?idApp=<?php echo (int)$respAplic['idAplicacion'] ?>">

           <?php if ($numPagos > 1){ ?>
                    <div class="form-group row">
                       <label class="col-sm-4 col-form-label">Nombre de la instancia: </label>
                       <div class="col-sm-2">
                          <label class="col-sm-3 col-form-label"><?php echo remove_junk($nomInstancia);?></label>
                       </div>
                    </div>
           <?php }else{ ?>
                    <div class="form-group row">
                       <label class="col-sm-4 col-form-label">Nombre de la instancia:</label>
                       <div class="col-sm-2">
                          <input type="text" class="form-control" name="nomInstancia" value="<?php echo remove_junk($nomInstancia);?>">
                       </div>
                    </div>
           <?php } ?>

           <?php if ($numPagos > 1){ ?>
                    <div class="form-group row">
                       <label class="col-sm-4 col-form-label">Nombre de la base de datos: </label>
                       <div class="col-sm-2">
                          <label class="col-sm-3 col-form-label"><?php echo remove_junk($nomBaseDatos);?></label>
                       </div>
                    </div>
           <?php }else{ ?>
                    <div class="form-group row">
                       <label class="col-sm-4 col-form-label">Nombre de la base de datos:</label>
                       <div class="col-sm-2">
                          <input type="text" class="form-control" name="nomBaseDatos" value="<?php echo remove_junk($nomBaseDatos);?>">
                       </div>
                    </div>
           <?php } ?>

           <?php if ($numPagos > 1){ ?>
                    <div class="form-group row">
                       <label class="col-sm-4 col-form-label">Tipo: </label>
                       <div class="col-sm-2">
                          <label class="col-sm-3 col-form-label"><?php echo remove_junk($tipo);?></label>
                       </div>
                    </div>
           <?php }else{ ?>
                    <div class="form-group row">
                       <label class="col-sm-4 col-form-label">Tipo:</label>
                       <div class="col-sm-3">
                          <select class="form-control" name="tipo">
                             <option value="">Selecciona el tipo de aplicación</option>
                             <option value="Condor">Condor</option>
                             <option value="Balam">Balam</option>
                          </select>
                       </div>
                    </div>
           <?php } ?>

           <?php if ($numPagos > 1){ ?>
                    <div class="form-group row">
                       <label class="col-sm-4 col-form-label">Pago inicial: </label>
                       <div class="col-sm-2">
                          <label class="col-sm-3 col-form-label"><?php echo remove_junk($pagoInicial);?></label>
                       </div>
                    </div>
           <?php }else{ ?>
                    <div class="form-group row">
                       <label class="col-sm-4 col-form-label">Pago inicial:</label>
                       <div class="col-sm-2">
                          <input type="number" step="0.01" class="form-control" name="pagoInicial" value="<?php echo remove_junk($pagoInicial);?>">
                       </div>
                    </div>
           <?php } ?>

           <div class="form-group row">
              <label class="col-sm-4 col-form-label">Pago del período:</label>
              <div class="col-sm-2">
                 <input type="number" step="0.01" class="form-control" name="pagoPeriodo" value="<?php echo remove_junk($pagoDelPeriodo);?>">
              </div>
           </div>

           <div class="form-group row">
              <label class="col-sm-4 col-form-label">Período:</label>
              <div class="col-sm-3">
                 <select class="form-control" name="periodo">
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
                 <input type="date" name="fechaInicio" min="<?php echo $fecha_actual ?>" value="<?php echo remove_junk($fechaIniPeriodo); ?>">
              </div>
           </div>

           <div class="form-group row">
              <label class="col-sm-4 col-form-label">Forma de pago:</label>
              <div class="col-sm-3">
                 <select class="form-control" name="tipoPago">
                    <option value="">Selecciona una forma de pago</option>
                    <?php  foreach ($tipos_pago as $forma): ?>
                    <option value="<?php echo $forma['id_pago']; ?>" <?php if($respAplic['tipoPago'] === $forma['id_pago']): echo "selected"; endif; ?> >
                    <?php echo remove_junk($forma['tipo_pago']); ?></option>
                    <?php endforeach; ?>
                 </select>
              </div>
           </div>  

         </div>

         <input type="hidden" name="tipoAux" value="<?php echo $tipo ?>">
         <input type="hidden" name="pagoInicialAux" value="<?php echo $pagoInicial ?>">
         <input type="hidden" name="pagoPeriodoAux" value="<?php echo $pagoDelPeriodo ?>">
         <button type="submit" name="editar" class="btn btn-danger">Editar</button>
       </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('../layouts/footer.php'); ?>
