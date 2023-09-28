<?php
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);

  $producto = buscaRegistroPorCampo("prodsvendidos","idProdVendido",$_GET['idProducto']);
  if(!$producto){
    $session->msg("d","idProdVendido vacío");
    redirect('productosVendidos.php');
  }

  $resultado = borraRegistroPorCampo("prodsvendidos","idProdVendido",$producto['idProdVendido']);

  if($resultado){
     $session->msg("s","Producto eliminado");
     redirect('productosVendidos.php');
  }else{
     $session->msg("d","Falló la eliminación");
     redirect('productosVendidos.php');
  }
?>
