<?php
   $page_title = 'Cortes de las quincenas';
   require_once('../../modelo/load.php');
   
   // Checkin What level user has permission to view this page
   page_require_level(1);
   
   $encargados = find_all('users');

   $meses = array('01'=>'Enero',
                  '02'=>'Febrero',
                  '03'=>'Marzo',
                  '04'=>'Abril',
                  '05'=>'Mayo',
                  '06'=>'Junio',
                  '07'=>'Julio',
                  '08'=>'Agosto',
                  '09'=>'Septiembre',
                  '10'=>'Octubre',
                  '11'=>'Noviembre',
                  '12'=>'Diciembre');

   $c_idEncargado = (isset($_POST['encargado']) && $_POST['encargado']!='') ? remove_junk($db->escape($_POST['encargado'])):'';

   $mes =  (isset($_POST['mes'])  && $_POST['mes']!='')  ? $_POST['mes']  : date('m');
   $anio = (isset($_POST['anio']) && $_POST['anio']!='') ? $_POST['anio'] : date('Y');

   $fechaInicial = $anio."/".$mes."/01";
?>

<?php include_once('../layouts/header.php'); ?>

<script type="text/javascript" src="../../libs/js/general.js"></script>

<!DOCTYPE html>
<html>
<head>
   <title> Cortes de las quincenas </title>
</head>

<body onload="focoEncargado();">
   <form name="form1" method="post" action="cortes_quincena.php">
   <?php
      $fechaInicioPQ = date('Y/m/d', strtotime($fechaInicial));

      $fechaFinPQ =   date("d-m-Y",strtotime($fechaInicioPQ."+ 14 days"));
      $fechaFinalPQ = date ('Y/m/d',strtotime($fechaFinPQ));
      $fechaIniPQ =   date ('d-m-Y',strtotime($fechaInicioPQ));

      $fechaIniSQ =    date("d-m-Y",strtotime($fechaInicioPQ."+ 15 days"));
      $fechaInicioSQ = date ('Y/m/d',strtotime($fechaIniSQ));
      $dia =           date('t', strtotime($fechaIniSQ));
      $mes =           date('m', strtotime($fechaIniSQ));
      $anio =          date('Y', strtotime($fechaIniSQ));
      $fechaFinSQ =    $anio."/".$mes."/".$dia;
      $fechaFinSQ =    date ('d-m-Y',strtotime($fechaFinSQ));
      $fechaFinalSQ =  date ('Y/m/d',strtotime($fechaFinSQ));

      if ($c_idEncargado!=""){
         $result =   find_by_id("users",$c_idEncargado);
         $cortesPQ = cortePeriodoVen($result['username'],$fechaInicioPQ,$fechaFinalPQ);
         $cortesSQ = cortePeriodoVen($result['username'],$fechaInicioSQ,$fechaFinalSQ);
      }else{
         $cortesPQ = cortePeriodo($fechaInicioPQ,$fechaFinalPQ);
         $cortesSQ = cortePeriodo($fechaInicioSQ,$fechaFinalSQ);
      }
   ?>

   <div class="row col-md-12">
      <?php echo display_msg($msg); ?>
   </div>
   <div class="row col-md-12">
      <div class="panel panel-default">
         <div class="panel-heading clearfix">
            <div class="form-group">
               <div class="col-md-3">
                  <select class="form-control" name="encargado">
                     <option value="">Vendedor</option>
                     <?php  foreach ($encargados as $id): ?>
                     <option value="<?php echo (int)$id['id'] ?>">
                     <?php echo $id['name'] ?></option>
                     <?php endforeach; ?>
                  </select>
               </div>                 
               <div class="col-md-3">
                  <select class="form-control" name="anio">
                     <option value="">Año</option>
                     <?php $i = (int)2020; while ($i<=2040): ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                     <?php $i++; endwhile; ?>
                  </select>
               </div>  
               <div class="col-md-3">
                  <select class="form-control" name="mes">
                     <option value="">Mes</option>
                     <?php foreach ($meses as $mesNum => $mesNom): ?>
                        <option value="<?php echo $mesNum ?>"> <?php echo $mesNom ?> </option>
                     <?php endforeach ?>
                  </select>
               </div>  
               <a href="#" onclick="corteQuincena();" class="btn btn-primary">Buscar</a>
            </div>   
         </div>
         
         <?php if (count($cortesPQ) > 0) { ?>
            <div class="panel-body">
               <span><strong><?php echo "Quincena del: $fechaIniPQ al: $fechaFinPQ"; ?></strong></span>
            </div>
            <div class="panel-body">
               <table class="table table-bordered">
                  <thead>
                     <tr>
                        <th class="text-left" style="width: 25%;">   Vendedor </th>
                        <th class="text-center" style="width: 25%;"> Sucursal </th>
                        <th class="text-center" style="width: 25%;"> Venta    </th>
                        <th class="text-center" style="width: 25%;"> Ganancia </th>
                     </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($cortesPQ as $cortePQ): ?>
                     <tr>
                        <td><?php echo remove_junk($cortePQ['vendedor']); ?></td>
                        <td class="text-center"><?php echo remove_junk($cortePQ['nom_sucursal']); ?></td>
                        <td class="text-right"> <?php echo "$".money_format("%.2n",$cortePQ['venta']); ?></td>
                        <td class="text-right"> <?php echo "$".money_format("%.2n",$cortePQ['ganancia']); ?></td>
                     </tr>
                  <?php endforeach; ?>
                  </tbody>
               </table>
            </div>
         <?php } ?>
         <?php if (count($cortesSQ) > 0){ ?>
            <div class="panel-body">
               <span><strong><?php echo "Quincena del: $fechaIniSQ al: $fechaFinSQ"; ?></strong></span>
            </div>
            <div class="panel-body">
               <table class="table table-bordered">
                  <thead>
                     <tr>
                        <th class="text-left" style="width: 25%;">   Vendedor </th>
                        <th class="text-center" style="width: 25%;"> Sucursal </th>
                        <th class="text-center" style="width: 25%;"> Venta    </th>
                        <th class="text-center" style="width: 25%;"> Ganancia </th>
                     </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($cortesSQ as $corteSQ): ?>
                     <tr>
                        <td><?php echo remove_junk($corteSQ['vendedor']); ?></td>
                        <td class="text-center"><?php echo remove_junk($corteSQ['nom_sucursal']); ?></td>
                        <td class="text-right"> <?php echo "$".money_format("%.2n",$corteSQ['venta']); ?></td>
                        <td class="text-right"> <?php echo "$".money_format("%.2n",$corteSQ['ganancia']); ?></td>
                     </tr>
                  <?php endforeach; ?>
                  </tbody>
               </table>
            </div>
         <?php } ?>
      </div>
   </div>
</form>
</body>
</html>

<?php include_once('../layouts/footer.php'); ?>