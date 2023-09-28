<?php
  $page_title = 'Lista de aplicaciones por vencer';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  ini_set('date.timezone','America/Mexico_City');
?>
<?php include_once('../layouts/header.php'); ?>
<script type="text/javascript" src="../../libs/js/general.js"></script>

<!DOCTYPE html>
<html>
<head>
<title>Lista de aplicaciones por vencer</title>
</head>
<body onload="focoInstancia();">
  <form name="form1" method="post" action="appsActivas.php">
          <br>
<?php

  $datoInstancia= isset($_POST['instancia']) ? $_POST['instancia']:'';
 
  if ($datoInstancia != "") {
     $aplicaciones = appsPorVencerInstancia($datoInstancia);
  }else{
     $aplicaciones = appsPorVencer();    
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
                  <input type="text" class="form-control" name="instancia" long="21">
                </div>
              </div>  
              <a href="#" onclick="aplicaciones();" class="btn btn-primary">Buscar</a> 
              <strong>
                 <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                 <span class="glyphicon glyphicon-th"></span>
                 <span>Aplicaciones activas</span>
              </strong>
           </div>   
          </div>   
         </div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="text-center" style="width: 47%;"> Cliente </th>
                <th class="text-center" style="width: 18%;"> Instancia </th>
                <th class="text-center" style="width: 5%;"> Tipo </th>
                <th class="text-center" style="width: 10%;"> Nombre BD </th>
                <th class="text-center" style="width: 10%;"> Fecha Inicio </th>
                <th class="text-center" style="width: 10%;"> Pago por período </th>
                <th class="text-center" style="width: 10%;"> Período </th>
                <th class="text-center" style="width: 10%;"> Fecha vencimiento </th>
                <th class="text-center" style="width: 5%;"> Acciones </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($aplicaciones as $aplicacion):?>
              <tr>
                <?php 

                   $fechaDelDia = date('Y-m-d',time());

                   $fechaRenovacion = date("Y-m-d", strtotime ($aplicacion['fechaRenovacion']));
                   $diaRen = date("d", strtotime ($fechaRenovacion));
                   $mesRen = date("m", strtotime ($fechaRenovacion));
                   $anioRen = date("Y", strtotime ($fechaRenovacion)); 

                   $fechaVenc = date("Y-m-d", mktime(0,0,0, $mesRen+$aplicacion['periodo'],$diaRen,$anioRen));

                   $fechaVencimiento = date("Y-m-d", strtotime ($fechaVenc));

                   $fechaRen = date("Y-m-d", mktime(0,0,0, $mesRen,$diaRen,$anioRen));
                   $fechaIniPer = new DateTime($fechaRen);
                   $fechaVencto = new DateTime($fechaVencimiento);
                   $fecha_actual = new DateTime(date('Y-m-d',time()));

                   $diferencia = date_diff($fecha_actual,$fechaVencto);
                   $difActual = date_diff($fecha_actual,$fechaIniPer);

                   $meses = $diferencia->m;
                   $anios = $diferencia->y;
                   $dias = $diferencia->d;

                   $mesActual = $difActual->m;
                   $anioActual = $difActual->y;
                   $diaActual = $difActual->d;

                   if ($dias >= 0 && $meses > 0 && $anios >= 0 && $fechaDelDia <= $fechaVencimiento){
                ?>
                      <td><?php echo remove_junk($aplicacion['nom_cliente']); ?></td>
                      <td><?php echo remove_junk($aplicacion['nomInstancia']); ?></td>
                      <td><?php echo remove_junk($aplicacion['tipo']); ?></td>
                      <td><?php echo remove_junk($aplicacion['nomBaseDatos']); ?></td>
                      <?php if ($aplicacion['fechaInicio'] == $aplicacion['fechaRenovacion']){ ?>
                         <td class="text-center"><?php echo date("d-m-Y", strtotime ($aplicacion['fechaInicio'])); ?></td>
                      <?php }else{ ?>
                         <td class="text-center"><?php echo date("d-m-Y", strtotime ($aplicacion['fechaRenovacion'])); ?></td>
                      <?php } ?>
                      </td>
                      <?php  if ($aplicacion['pagoPeriodo'] == "0.00" ){ ?>
                         <td class="text-right"> <?php echo money_format('%.2n',$aplicacion['pagoPeriodoAcord']); ?></td>
                      <?php }else{ ?>
                         <td class="text-right"> <?php echo money_format('%.2n',$aplicacion['pagoPeriodo']); ?></td>
                      <?php } ?>
                      <td><?php echo remove_junk($aplicacion['nombre']); ?></td>
                      <td class="text-center"><?php echo date("d-m-Y", strtotime ($fechaVenc)); ?></td>
                      <td class="text-center">
                         <div class="btn-group">
                            <?php if (($diaActual > 0 && $mesActual == 0 && $anioActual == 0) || ($fecha_actual < $fechaIniPer)){ ?>
                               <a href="editarApp.php?idApp=<?php echo (int)$aplicacion['idAplicacion'];?>" class="btn btn-info btn-xs" title="Editar" data-toggle="tooltip">
                               <span class="glyphicon glyphicon-edit"></span>
                               </a>
                               <a href="deleteApp.php?idApp=<?php echo (int)$aplicacion['idAplicacion'];?>" class="btn btn-danger btn-xs" title="Eliminar" data-toggle="tooltip">
                               <span class="glyphicon glyphicon-trash"></span>
                               </a>
                            <?php } ?>
                         </div>
                      </td>
                <?php } ?>
              </tr>
              <?php break; ?>
             <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</form>
</body>
</html>
<?php include_once('../layouts/footer.php'); ?>
