<?php
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);

  $categorie = find_by_id('categories',(int)$_GET['id']);
  if(!$categorie){
     $session->msg("d","Falta el ID de la categoría.");
     redirect('categorie.php');
  }

  $delete_id = delete_by_id('categories',(int)$categorie['id']);

  if($delete_id){
     $session->msg("s","Categoría eliminada.");
     redirect('categorie.php');
  }else{
     $session->msg("d","falló la eliminación.");
     redirect('categorie.php');
  }
?>
