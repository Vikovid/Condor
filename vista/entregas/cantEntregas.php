<?php
require_once('../../modelo/load.php');
$multiplos= isset($_POST['multiplos']) ? $_POST['multiplos']:'';
$multAux = $multiplos;
$aux = explode("|", $multAux);
$cont = 0;
$exceso = 0;
$usuario= isset($_POST['user']) ? $_POST['user']:'';

$productos = buscaProdsTempEntregas($usuario);

foreach ($productos as $producto):
   $cve_temporal=$producto['cve_temporal'];
   $cantTemp = $producto['cantidad'];
   $precio = $producto['sale_price'];
   $cantproducto = $producto['quantity'];
   $nomProducto = $producto['name'];
   $cantCaja = $producto['cantidadCaja'];
   $porcMay = $producto['porcentajeMayoreo'];
   $productoId = $producto['id'];
   $multiplo = $aux[$cont];
   $cont++;
   $intMultiplo = (float)$multiplo;
   $intPrecio = (float)$precio;
   $precioMult = $intMultiplo * $intPrecio;

   $respTotal = sumaCampo('cantidad','tempentregas','product_id',$productoId);

   if ($respTotal != null)
      $sumaTemp = $respTotal['total'];

   if ($multiplo == "")
      $multiplo = 0;
   
   $cantAux = $multiplo - $cantTemp;
   $cantProdTemp = $sumaTemp + $cantAux;

   if ($cantProdTemp <= $cantproducto){ 
      if ($cantProdTemp >= $cantCaja){
         if ($precioCaja > 0 && $cantCaja > 0){
            $precioProd = $precioCaja/$cantCaja;
            $precioMult = $precioProd * $intMultiplo;
         }
      }
      actCantidad('tempentregas','cantidad',$intMultiplo,$precioMult,$cve_temporal);
   }else{
      $exceso = 1;
      break;
   }
endforeach;
if ($exceso == 1) {
   echo "<script> alert('Está solicitando más ' + '".$nomProducto."' + ' del disponible');</script>";
}
echo '<script> window.location="registroEntrega.php";</script>';	
?>
