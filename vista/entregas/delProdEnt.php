<?php
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);

  $idEntrega = isset($_GET['id']) ? $_GET['id']:'';

  $respEntrega = buscaRegistroPorCampo('entregas','id_entrega',$idEntrega);
  $idGrupoEnt = $respEntrega['idGrupoEnt'];  

  $respCont = cuentaRegistros('id_entrega','entregas','idGrupoEnt',$idGrupoEnt);
  $total = $respCont['total'];

  if ($total == "1"){
     $respAct = actEstadoEntrega('4',$idGrupoEnt);
   
     if($respAct){
       $session->msg("s","Producto eliminado");
       redirect('entregas.php');
     }else{
       $session->msg("d","Falló la eliminación");
       redirect('editarEntrega.php?id='.$idGrupoEnt);
     }
  }else{
     $sqlResp = borraRegistroPorCampo('entregas','id_entrega',$idEntrega);

     if($sqlResp){
       $session->msg("s","Producto eliminado");
       redirect('editarEntrega.php?id='.$idGrupoEnt);
     }else{
       $session->msg("d","Falló la eliminación");
       redirect('editarEntrega.php?id='.$idGrupoEnt);
     }
  }
?>
