<?php
require_once('../../modelo/load.php');
$user = current_user(); 
$userId = $user['id'];
$idSucursal = $user['idSucursal'];
$usuario = $user['name'];

ini_set('date.timezone','America/Mexico_City');
$fecha_actual=date('Y-m-d',time());
$hora_actual=date('H:i:s',time());

$vendedor = isset($_POST['vendedor']) ? $_POST['vendedor']:'';
$fechaEntrega = isset($_POST['fecha']) ? $_POST['fecha']:'';
$horaEntrega = isset($_POST['hora']) ? $_POST['hora']:'';
$estado = isset($_POST['estatus']) ? $_POST['estatus']:'';
$cantNeg = 0;
$idGrupoEnt = "";

$horaEnt = date("H:i:s", strtotime ($horaEntrega));

$consGrupoEnt = buscaRegistroMaximo('entregas','idGrupoEnt');

if ($consGrupoEnt != null)
   $idGrupoEnt = $consGrupoEnt['idGrupoEnt'];

if ($idGrupoEnt == ""){
   $idGrupoEnt = 1;
}else{
   $idGrupoEnt = $idGrupoEnt + 1;
}

$resultado3 = entregasRegistrar($usuario,$idSucursal);
$resultado4 = entregasRegistrar($usuario,$idSucursal);

foreach ($resultado3 as $res3):

   $cantTemp = $res3['cantidad'];
   $cantProd = $res3['quantity'];
   $prodName = $res3['name'];

   $resta = $cantProd - $cantTemp;

   if ($resta < 0){
      $cantNeg++;
      break;
   }
endforeach;

if ($cantNeg == 0){

    foreach ($resultado4 as $res4):
      $idProducto=$res4['product_id'];
      $cantidad = $res4['cantidad'];
      $precio = $res4['precio'];
      $nomProducto = $res4['name'];
      $codigo = $res4['Codigo'];

      altaEntregas($userId,$idProducto,$nomProducto,$codigo,$vendedor,$cantidad,$precio,$fechaEntrega,$horaEnt,$estado,$idSucursal,$fecha_actual,$hora_actual,$idGrupoEnt);
    endforeach;

   borraRegistroPorCampo('tempentregas','usuario',$usuario);

   echo '<script> window.location="registroEntrega.php";</script>';
}

if ($cantNeg > 0){
   echo "<script> alert('El producto: ' + '".$prodName."' + ' ya no tiene stock');</script>";
   echo '<script> window.location="registroEntrega.php";</script>';   
}
?>
