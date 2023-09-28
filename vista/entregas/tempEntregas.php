<?php
require_once('../../modelo/load.php');
$user = current_user(); 

$id_producto= isset($_POST['idProd']) ? $_POST['idProd']:'';
$precio= isset($_POST['precio']) ? $_POST['precio']:'';
$usuario = $user['name'];
$idSucursal = $user['idSucursal'];
$cveTemporal = "";

$consTempEntregas = buscaRegistroMaximo('tempentregas','cve_temporal');

if ($consTempEntregas != null){
   $cveTemporal = $consTempEntregas['cve_temporal'];
}

if ($cveTemporal == ""){
   $id = 1;
}else{
   $id = $cveTemporal + 1;
}

$respSuma = sumaCampo('cantidad','tempentregas','product_id',$id_producto);

if ($respSuma != null)
   $sumaTemp = $respSuma['total'] + 1;
else
   $sumaTemp = 1;

$producto = find_by_id("products",$id_producto);
$cantProd = $producto['quantity'];
$nomProducto = $producto['name'];

if ($sumaTemp <= $cantProd){
    altaTempEntregas($id,$id_producto,'1',$precio,$usuario,$idSucursal);
}else{
   echo "<script> alert('Está solicitando más ' + '".$nomProducto."' + ' del disponible');</script>";
}
echo '<script> window.location="registroEntrega.php";</script>';	
?>
