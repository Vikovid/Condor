<?php
  $page_title = 'Lista de productos';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(5);
  $user = current_user(); 
  $nivel = $user['user_level'];

  $all_categorias = find_all('categories');

  $p_scu = "";

  if (isset($_POST['categoria'])){  
     $p_scu =  remove_junk($db->escape($_POST['categoria']));//prueba
  }

  $codigo= isset($_POST['Codigo']) ? $_POST['Codigo']:'';

  if ($p_scu!=""){
     if ($codigo!="") {
        if (is_numeric($codigo)){
           $products = join_product_table1($codigo,$p_scu);
        }else{
           $products = join_product_table2($codigo,$p_scu);
        }
     }else{
        $products = join_select_categories($p_scu);
     }
  }else{
     if ($codigo!="") {
        if (is_numeric($codigo)){
           $products = join_product_table1a($codigo);
        }else{
           $products = join_product_table2a($codigo);
        }
     }else{
        $products = join_product_table();
     }
  }
?>
<?php include_once('../layouts/header.php'); ?>
<script type="text/javascript" src="../../libs/js/general.js"></script>

<!DOCTYPE html>
<html>
<head>
<title>Lista de productos</title>
</head>

<body onload="foco();">
  <form name="form1" method="post" action="simple_product.php">

  <div class="row">
     <div class="col-md-12">
        <?php echo display_msg($msg); ?>
     </div>
  </div>   
  <div class="col-md-12">
     <div class="panel panel-default">
        <div class="panel-heading clearfix">
           <div class="pull-right">
              <div class="form-group">
                 <div class="col-md-4">
                    <div class="input-group">
                       <span class="input-group-addon">
                          <i class="glyphicon glyphicon-barcode"></i>
                       </span>
                       <input type="text" class="form-control" name="Codigo" long="21" oninput="mayusculas(event)">
                    </div>
                 </div>
                 <a href="#" onclick="producto();" class="btn btn-primary">Buscar</a> 
                 <img src="../../libs/imagenes/Logo.png" height="50" width="50" alt="" align="center">
              </div>   
           </div>
        </div>
        <div class="panel-body">
           <table class="table table-bordered">
              <thead>
                 <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th> Imagen</th>
                    <th> Descripción </th>
                    <th class="text-center" style="width: 10%;"> Stock </th>
                    <th class="text-center" style="width: 10%;"> Precio de venta </th>
                    <th class="text-center" style="width: 10%;"> Agregado </th>
                    <th class="text-center" style="width: 10%;"> Sucursal </th>
                    <?php if ($nivel != "5"){ ?>
                       <th class="text-center" style="width: 10%;"> Acción </th>
                    <?php } ?>
                 </tr>
              </thead>
              <tbody>
                 <?php foreach ($products as $product):?>
                 <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td class="text-center">
                    <?php if ($product['foto'] != ""){ 
                       echo "<img src='data:image/jpg; base64,".base64_encode($product['foto'])."' width='45' height='50'>";
                    } ?> 
                    </td>
                    <td><?php echo remove_junk($product['name']); ?></td>
                    <td class="text-center"><?php echo remove_junk($product['quantity']); ?></td>
                    <td class="text-center"><?php echo remove_junk($product['sale_price']); ?></td>
                    <td class="text-center"><?php echo date("d-m-Y", strtotime ($product['fechaRegistro'])); ?></td>
                    <td class="text-center"><?php echo remove_junk($product['sucursal']); ?></td>
                    <?php if ($nivel != "5"){ ?>
                       <td class="text-center">
                          <div class="btn-group">
                             <a href="../productos/edit_verStockProduct.php?id=<?php echo (int)$product['id'];?>" class="btn btn-success btn-xs" title="Stock" data-toggle="tooltip">
                             <span class="glyphicon glyphicon-pencil"></span>
                             </a>
                          </div>
                       </td>
                    <?php } ?>
                 </tr>
                 <?php endforeach; ?>
              </tbody>
           </table>
        </div>
     </div>
  </div>
</form>
</body>
</html>
<?php include_once('../layouts/footer.php'); ?>
