<?php
  $page_title = 'Detalle de aplicaciones';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  $idCredencial = isset($_GET['idCredencial']) ? $_GET['idCredencial']:'';

  $aplicaciones = buscaAplicaciones($idCredencial);
  $detAplicacion = buscaDetAplicacion($idCredencial);
  $cliente = buscaRegistroPorCampo('cliente','idCredencial',$idCredencial);
  $nomCliente = $cliente['nom_cliente'];
?>

<?php include_once('../layouts/header.php'); ?>

<body>
  <form name="form1" method="post" action="detalleApp.php">
  <br>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
     <div class="col-md-12">
        <div class="panel panel-default">
           <div class="panel-heading clearfix">
              <div class="form-group">
                 <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Detalle de Aplicaciones del cliente: </span>
                    <span><?php echo $nomCliente; ?></span>
                 </strong>
              </div>   
           </div>
        </div>
        <div class="col-md-12">
           <div class="panel-body">
              <table class="table table-bordered">
                 <tbody>
                 <?php foreach ($aplicaciones as $aplicacion):?>
                 <thead>
                 <tr>
                    <th class="text-center"> Instancia </th>
                    <th class="text-center"> Tipo </th>
                    <th class="text-center"> Nombre BD </th>
                    <th class="text-center"> Estado </th>
                 </tr>
                 </thead>
                 <tr>
                    <td class="text-center"> <?php echo remove_junk($aplicacion['nomInstancia']); ?></td>
                    <td class="text-center"> <?php echo remove_junk($aplicacion['tipo']); ?></td>
                    <td class="text-center"> <?php echo remove_junk($aplicacion['nomBaseDatos']); ?></td>
                    <?php if ($aplicacion['activo'] == "1"){ ?>
                        <td class="text-center"><?php echo "Activa"; ?></td>
                    <?php }else{ ?>
                        <td class="text-center"><?php echo "Inactiva"; ?></td>
                    <?php } ?>        
                 </tr>
                 <tr>
                    <th class="text-center" colspan="4">&nbsp;</th>
                 </tr>
                 <tr>
                    <th class="text-center"> Fecha Inicio </th>
                    <th class="text-center"> Fecha vencimiento </th>
                    <th class="text-center"> Período </th>
                    <th class="text-center"> Pago por período </th>
                 </tr>
                 <?php foreach ($detAplicacion as $detalle):?>
                 <tr>
                    <?php 
                       if ($detalle['fechaInicio'] == $detalle['fechaRenovacion']) 
                          $fechaIniPer = $detalle['fechaInicio'];
                       else
                          $fechaIniPer = $detalle['fechaRenovacion'];
                    ?>
                    <td class="text-center"><?php echo date("d-m-Y", strtotime ($fechaIniPer)); ?></td>
                    <?php
                        $fechaRenovacion = date("Y-m-d", strtotime ($fechaIniPer));
                        $diaRen = date("d", strtotime ($fechaRenovacion));
                        $mesRen = date("m", strtotime ($fechaRenovacion));
                        $anioRen = date("Y", strtotime ($fechaRenovacion)); 

                        $fechaVenc = date("d-m-Y", mktime(0,0,0, $mesRen+$detalle['periodo'],$diaRen,$anioRen));
                    ?>   
                    <td class="text-center"><?php echo $fechaVenc; ?></td>
                    <td class="text-center"><?php echo remove_junk($detalle['nombre']); ?></td>                  
                    <?php  if ($detalle['pagoPeriodo'] == "0.00" ){ ?>
                       <td class="text-center"> <?php echo money_format('%.2n',$aplicacion['pagoPeriodoAcord']); ?></td>
                    <?php }else{ ?>
                    <td class="text-center"> <?php echo money_format('%.2n',$detalle['pagoPeriodo']); ?></td>
                    <?php } ?>
                 </tr>
                 <?php endforeach; ?>
                 <tr>
                    <th class="text-center" colspan="4">&nbsp;</th>
                 </tr>
                 <?php endforeach; ?>
                 </tbody>
              </table>
           </div>
        </div>
     </div>
  </div>
</form>
</body>
<?php include_once('../layouts/footer.php'); ?>
