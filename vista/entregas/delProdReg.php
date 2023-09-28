<?php
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);

  $cveTemp = isset($_GET['cveTemp']) ? $_GET['cveTemp']:'';

  $sqlResp = borraRegistroPorCampo('tempentregas','cve_temporal',$cveTemp);

  if($sqlResp){
    $session->msg("s","Producto eliminado");
    redirect('registroEntrega.php');
  }else{
    $session->msg("d","Falló la eliminación");
    redirect('registroEntrega.php');
  }
?>
