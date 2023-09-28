<?php
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);

  if(isset($_GET['IdCredencial'])) {
     $id = $_GET['IdCredencial'];

     $resultado = borraRegistroPorCampo("cliente","idcredencial",$id);
  
     if(!$resultado) {
        die("falló la eliminación.");
     }
     $session->msg("s","Cliente eliminado correctamente.");
     redirect('cliente.php');
  } 
?>
