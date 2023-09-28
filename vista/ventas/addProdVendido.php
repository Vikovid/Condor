<?php
  $page_title = 'Agregar producto vendido';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);

  ini_set('date.timezone','America/Mexico_City');
  $fecha_actual = date('Y-m-d',time());

  if(isset($_POST['addProdVendido'])){
     $req_fields = array('nomProducto');
     validate_fields($req_fields);
     if(empty($errors)){
        $p_nomProducto = remove_junk($db->escape($_POST['nomProducto']));
        $p_marca = remove_junk($db->escape($_POST['marca']));
        $p_modelo = remove_junk($db->escape($_POST['modelo']));
        $p_codigo = remove_junk($db->escape($_POST['codigo']));
        $p_numSerie = remove_junk($db->escape($_POST['numSerie']));
        $p_nota = remove_junk($db->escape($_POST['nota']));

        $resultado = altaProdVendido($p_nomProducto,$p_marca,$p_modelo,$p_codigo,$p_numSerie,$p_nota,$fecha_actual);

        if($resultado){
           $session->msg('s',"Producto agregado exitosamente. ");
           redirect('productosVendidos.php', false);
        }else{
           $session->msg('d','Lo siento, falló el registro.');
           redirect('addProdVendido.php', false);
        }
     }else{
        $session->msg("d",$errors);
        redirect('addProdVendido.php',false);
     }
  }
?>
<?php include_once('../layouts/header.php'); ?>

<script language="Javascript">

function foco(){
  document.form1.nomProducto.focus();
}

function mayusculas(e) {
   var ss = e.target.selectionStart;
   var se = e.target.selectionEnd;
   e.target.value = e.target.value.toUpperCase();
   e.target.selectionStart = ss;
   e.target.selectionEnd = se;
}

</script>

<body onload="foco();">
<div class="row">
   <div class="col-md-12">
      <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
   <div class="col-md-9">
      <div class="panel panel-default">
         <div class="panel-heading">
            <strong>
               <span class="glyphicon glyphicon-th"></span>
               <span>Agregar producto vendido</span>
            </strong>
         </div>
         <div class="panel-body">
            <div class="col-md-12">
               <form method="post" name="form1" action="addProdVendido.php" class="clearfix">
               <div class="form-group">
                  <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-th-large"></i>
                     </span>
                     <input type="text" class="form-control" name="nomProducto" placeholder="Nombre" oninput="mayusculas(event)">
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-th-large"></i>
                     </span>
                     <input type="text" class="form-control" name="marca" placeholder="Marca" oninput="mayusculas(event)">
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-th-large"></i>
                     </span>
                     <input type="text" class="form-control" name="modelo" placeholder="Modelo" oninput="mayusculas(event)">
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-th-large"></i>
                     </span>
                     <input type="text" class="form-control" name="codigo" placeholder="Código de barras" oninput="mayusculas(event)">
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-th-large"></i>
                     </span>
                     <input type="text" class="form-control" name="numSerie" placeholder="Número de serie">
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-th-large"></i>
                     </span>
                     <textarea name="nota" class="form-control" placeholder="Nota" maxlength="200" rows="2" style="resize: none"></textarea>
                  </div>
               </div>
               <button type="submit" name="addProdVendido" class="btn btn-danger">Agregar</button>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
</body>
<?php include_once('../layouts/footer.php'); ?>
