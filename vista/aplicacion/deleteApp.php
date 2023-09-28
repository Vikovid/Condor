<?php
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  $idAplicacion = isset($_GET['idApp']) ? $_GET['idApp']:'';

  $delete = actParamsAplicacion($idAplicacion);

  if($delete){
    $session->msg("s","aplicacion eliminada");
    redirect('appsActivas.php');
  }else{
    $session->msg("d","Falló la eliminación");
    redirect('appsActivas.php');
  }
?>
