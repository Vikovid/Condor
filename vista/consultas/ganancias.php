<?php
  $page_title = 'Ganancias mensuales';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);

  $mes = "";
  $anio = "";
  $gananciaMens = 0;
 
  if(isset($_POST['mes'])){  
    $mes =  remove_junk($db->escape($_POST['mes']));//prueba
  }

  if(isset($_POST['anio'])){  
    $anio =  remove_junk($db->escape($_POST['anio']));//prueba
  }

  $parametros = find_by_id("parametros","1");

  $porcGanancia = $parametros['porcGanancia'];
?>
<?php include_once('../layouts/header.php'); ?>
<!DOCTYPE html>
<html>
<head>
<title>Ganancias mensuales</title>
</head>

<script language="Javascript">

function ganancias(){
  document.form1.action = "ganancias.php";
  document.form1.submit();
}

function foco(){
  document.form1.mes.focus();
}

</script>

<body onload="foco();">
  <form name="form1" method="post" action="ganancias.php">

<?php
   if ($mes == "" && $anio == ""){                          
      $month = date('m');
      $year = date('Y');
      $day = date("d", mktime(0,0,0, $month+1, 0, $year));
      $fechaInicial = $year."/".$month."/01";
      $fechaFinal = $year."/".$month."/".$day;
   }

   if ($mes != "" && $anio == ""){
      $year = date('Y');
      $fechaInicial = $year."/".$mes."/01";
      $numDias = date('t', strtotime($fechaInicial));
      $fechaFinal = $year."/".$mes."/".$numDias; 
   }

   if ($mes == "" && $anio != ""){
      $month = date('m');    
      $fechaInicial = $anio."/".$month."/01";
      $numDias = date('t', strtotime($fechaInicial));
      $fechaFinal = $anio."/".$month."/".$numDias; 
   }

   if ($mes != "" && $anio != ""){
      $fechaInicial = $anio."/".$mes."/01";
      $numDias = date('t', strtotime($fechaInicial));
      $fechaFinal = $anio."/".$mes."/".$numDias;
   }

   $fechaIni = date('Y/m/d', strtotime($fechaInicial));
   $fechaFin = date("Y/m/d", strtotime($fechaFinal));
   $fechIni = date ('d-m-Y', strtotime($fechaInicial));
   $fechFin = date ('d-m-Y', strtotime($fechaFinal));

   $ventaMens = ventasPeriodo($fechaIni,$fechaFin);    
   $gastoMens = gastosPeriodo($fechaIni,$fechaFin);

   $gananciaMens = $ventaMens['totalVentas'] - $gastoMens['total'];
?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="col-md-12">
   <div class="row">
      <div class="col-md-10">
         <div class="panel panel-default">
            <div class="panel-heading clearfix">
               <div class="form-group">
                  <div class="col-md-2">
                     <select class="form-control" name="mes">
                        <option value="">Mes</option>
                        <option value="01">Enero</option>
                        <option value="02">Febrero</option>
                        <option value="03">Marzo</option>
                        <option value="04">Abril</option>
                        <option value="05">Mayo</option>
                        <option value="06">Junio</option>
                        <option value="07">Julio</option>
                        <option value="08">Agosto</option>
                        <option value="09">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                     </select>
                  </div>  
                  <div class="col-md-2">
                     <select class="form-control" name="anio">
                        <option value="">Año</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                        <option value="2030">2030</option>
                        <option value="2031">2031</option>
                        <option value="2032">2032</option>
                        <option value="2033">2033</option>
                        <option value="2034">2034</option>
                        <option value="2035">2035</option>
                        <option value="2036">2036</option>
                        <option value="2037">2037</option>
                        <option value="2038">2038</option>
                        <option value="2039">2039</option>
                        <option value="2040">2040</option>
                     </select>
                  </div>  
                  <a href="#" onclick="ganancias();" class="btn btn-primary">Buscar</a>      
                  <img src="../../libs/imagenes/Logo.png" height="50" width="50" alt="" align="center">   
                  <strong>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "del $fechIni al $fechFin";?></strong>
               </div>
            </div>
            <div class="panel-body">
               <table class="table table-bordered table-striped">
                  <tbody>
                  <thead>
                     <tr>
                        <th class="text-center"> Total venta </th>
                        <th class="text-center"> Total gasto </th>
                        <th class="text-center"> Total ganancia </th>
                     </tr>
                  </thead>
                     <tr>
                        <td class="text-center"> <?php echo remove_junk($ventaMens['totalVentas']); ?></td>
                        <td class="text-center"> <?php echo remove_junk($gastoMens['total']); ?></td>
                        <td class="text-center"> <?php echo remove_junk($gananciaMens); ?></td>
                     </tr>
                     <tr>
                        <th class="text-center" colspan="3">&nbsp;</th>
                     </tr>
                     <tr>
                        <th class="text-center"> Ganancia GLSoftST </th>                
                        <th class="text-center"> Ganancia Luis </th>
                        <th class="text-center"> Ganancia Gustavo </th>
                     </tr>
                     <tr>
                     <?php 
                        $ganLuis = $gananciaMens * ($porcGanancia/100);
                        $ganGustavo = $gananciaMens * ($porcGanancia/100);
                        $ganGLSoftST = $gananciaMens * ((100 - 2*$porcGanancia)/100);
                     ?>
                        <td class="text-center"><?php echo money_format('%.2n',$ganGLSoftST); ?></td>
                        <td class="text-center"><?php echo money_format('%.2n',$ganLuis); ?></td>
                        <td class="text-center"><?php echo money_format('%.2n',$ganGustavo); ?></td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
</form>
</body>
<?php include_once('../layouts/footer.php'); ?>
