<?php
require_once('../../modelo/load.php');
page_require_level(5);

$multiplos= isset($_POST['multiplos']) ? $_POST['multiplos']:'';
$multAux = $multiplos;
$aux = explode("|", $multAux);
$cont = 0;
$exceso = 0;
$usuario= isset($_POST['user']) ? $_POST['user']:'';

$productos = buscaProdsTempVentas($usuario);

foreach ($productos as $producto):
   $cve_temporal = $producto['cve_temporal'];
   $cantTemp = $producto['qty'];
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

   $respSuma = sumaCampo('qty','temporal','product_id',$productoId);

   if ($respSuma != null)
      $sumaTemp = $respSuma['total'];

   if ($multiplo == "")
      $multiplo = 0;
   
   $cantAux = $multiplo - $cantTemp;
   $cantProdTemp = $sumaTemp + $cantAux;

   if ($cantProdTemp <= $cantproducto){ 
      if ($cantProdTemp >= $cantCaja){
         $porcentaje = 1 - ($porcMay/100);
         $precioMult = $precioMult * $porcentaje;
      }
      actCantidad('temporal','qty',$intMultiplo,$precioMult,$cve_temporal);
   }else{
      $exceso = 1;
      break;
   }
endforeach;
if ($exceso == 1) {
   echo "<script> alert('Está solicitando más ' + '".$nomProducto."' + ' del disponible');</script>";
}
echo '<script> window.location="add_sale.php";</script>';	
?>