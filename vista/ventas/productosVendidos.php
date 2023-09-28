<?php
   $page_title = 'Lista de productos vendidos';
   require_once('../../modelo/load.php');
   // Checkin What level user has permission to view this page
   page_require_level(2);
   //$productos = find_all("prodsvendidos");

   $modelo = "";
   $codigo = "";
   $numSerie = "";
  
   if(isset($_POST['modelo'])){  
      $modelo = remove_junk($db->escape($_POST['modelo']));//prueba
   }

   if(isset($_POST['codigo'])){  
      $codigo = remove_junk($db->escape($_POST['codigo']));//prueba
   }

   if(isset($_POST['numSerie'])){  
      $numSerie =  remove_junk($db->escape($_POST['numSerie']));//prueba
   }

   $productos = buscaProdVendido($modelo,$codigo,$numSerie);

?>
<?php include_once('../layouts/header.php'); ?>

<script language="Javascript">

function foco(){
  document.form1.modelo.focus();
}

function mayusculas(e) {
   var ss = e.target.selectionStart;
   var se = e.target.selectionEnd;
   e.target.value = e.target.value.toUpperCase();
   e.target.selectionStart = ss;
   e.target.selectionEnd = se;
}

function buscaProd(){
  document.form1.action = "productosVendidos.php";
  document.form1.submit();
}

</script>

<body onload="foco();">
  <form name="form1" method="post" action="productosVendidos.php">

<div class="row">
   <div class="col-md-12">
      <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-12">
      <div class="panel panel-default">
         <div class="panel-heading clearfix">
            <div class="form-group">
               <div class="col-md-2">
                  <input type="text" class="form-control" name="modelo" placeholder="modelo" oninput="mayusculas(event)">
               </div>  
               <div class="col-md-2">
                  <input type="text" class="form-control" name="codigo" placeholder="Código de barras" oninput="mayusculas(event)">
               </div>  
               <div class="col-md-2">
                  <input type="text" class="form-control" name="numSerie" placeholder="No. de Serie">
               </div>  
               <a href="#" onclick="buscaProd();" class="btn btn-primary">Buscar</a>      
               <a href="addProdVendido.php" class="btn btn-primary">Agregar Producto</a>  
               <img src="../../libs/imagenes/Logo.png" height="50" width="50" alt="" align="center">   
            </div>
         </div>
         <div class="panel-body">
            <table class="table table-bordered">
               <thead>
                  <tr>
                     <th class="text-center" style="width: 30%;"> Producto </th>
                     <th class="text-center" style="width: 9%;"> Marca </th>
                     <th class="text-center" style="width: 9%;"> Modelo </th>
                     <th class="text-center" style="width: 9%;"> Código de barras </th>
                     <th class="text-center" style="width: 10%;"> No. Serie </th>
                     <th class="text-center" style="width: 8%;"> Fecha </th>
                     <th class="text-center" style="width: 20%;"> Notas </th>
                     <th class="text-center" style="width: 5%;"> Acciones </th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($productos as $producto):?>
                  <tr>
                     <td> <?php echo remove_junk($producto['nomProducto']); ?></td>
                     <td class="text-center"> <?php echo remove_junk($producto['marca']); ?></td>
                     <td class="text-center"> <?php echo remove_junk($producto['modelo']); ?></td>
                     <td class="text-center"> <?php echo remove_junk($producto['codigo']); ?></td>
                     <td class="text-center"> <?php echo remove_junk($producto['numSerie']); ?></td>
                     <td class="text-center"> <?php echo date("d-m-Y", strtotime ($producto['fecha'])); ?></td>
                     <td><textarea name="nota" class="form-control" maxlength="200" rows="2" style="resize: none" readonly><?php echo remove_junk($producto['nota']); ?></textarea></td>
                     <td class="text-center">
                        <div class="btn-group">
                           <a href="editarProdVendido.php?idProducto=<?php echo (int)$producto['idProdVendido'];?>" class="btn btn-info btn-xs"  title="Editar" data-toggle="tooltip">
                           <span class="glyphicon glyphicon-edit"></span>
                           </a>
                           <a href="borrarProdVendido.php?idProducto=<?php echo (int)$producto['idProdVendido'];?>" class="btn btn-danger btn-xs"  title="Eliminar" data-toggle="tooltip">
                           <span class="glyphicon glyphicon-trash"></span>
                           </a>
                        </div>
                     </td>
                  </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
</form>
</body>
<?php include_once('../layouts/footer.php'); ?>
