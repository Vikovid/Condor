<?php
  $page_title = 'Lista de sucursales';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
  $user = current_user(); 
  $usuario = $user['name'];
  $usu = $user['id'];
  $sucursal = $user['idSucursal'];
  $vendedores = find_all('users');
  ini_set('date.timezone','America/Mexico_City');
  $fecha_actual=date('Y-m-d',time());

  $codigo="";
  $aux="|";  

  $codigo= isset($_POST['codigo']) ? $_POST['codigo']:'';  
?>
<?php include_once('../layouts/header.php'); ?>
<script type="text/javascript" src="../../libs/js/general.js"></script>

<!DOCTYPE html>
<html>
<head>
   <title>Registro de entregas</title>
</head>

<body onload="focoCodigo();">
  <form name="form1" method="post" action="registroEntrega.php">
<?php
if($codigo!=""){
   $productos = buscaProductosCod($codigo,$usu,$sucursal);
}else{
   $productos = buscaProducto($usu,$sucursal);
}

$prodsSeleccionados = buscaProdsTempEntregas($usuario);
$respTotal = sumaCampo('precio','tempentregas','usuario',$usuario);
$total = $respTotal['total'];
?>
<div class="row">
   <div class="col-md-12">
      <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-12">
      <div class="panel panel-default">
         <div class="panel-heading clearfix">
            <div class="form-group">
               <div class="col-md-5">
                  <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-barcode"></i>
                     </span>
                     <td width="40%"><input name="codigo" type="text" class="form-control" id="busqueda" size="51" maxlength="50" onclick="mayusculas(event);"></td>
                  </div>
               </div>  
               <input type="submit" id="boton" class="btn btn-primary" name="Submit" value="Buscar" />
               <img src="../../libs/imagenes/Logo.png" height="50" width="70" alt="" align="center">
            </div>   
         </div>   
      </div>
   </div>
   <div class="col-md-11">
      <div class="panel-body">
         <table class="table table-bordered">
            <thead>
            <tr>
               <td width="3%">Sel</td>
               <td width="70%">Nombre</td>
               <td width="27%">Precio</td> 
            </tr>
            </thead>
            <tbody>
               <?php foreach ($productos as $res):?>
                  <tr>
                     <td width="3%"><input type='radio' name='empresa' value='<?php echo $res["id"].$aux.$res["sale_price"] ?>' onClick='agregarEnt();'/></td>
                     <td width="70%"><?php echo $res['name'] ?></td>
                     <td width="27%"><?php echo $res['sale_price'] ?></td>
                  </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   </div>
   <div class="col-md-12">
      <div class="panel panel-default">
         <div class="panel-heading clearfix">
            <div class="form-group">
               <table align="right">       
                  <tr>
                     <td width="5%" style="font-size:15px"><b>Vendedor:</td>
                     <td width="10%">
                        <div class="input-group">
                           <select class="form-control" name="vendedor">
                              <?php foreach ($vendedores as $id): ?>
                              <option value="<?php echo $id['username']; ?>" <?php if($usuario === $id['name']): echo "selected"; endif; ?> >
                              <?php echo $id['name'] ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                     </td>
                     <td width="10%" style="font-size:15px"><b>&nbsp;&nbsp;&nbsp;Fecha entrega:</td>      
                     <td width="10%" >
                        <div class="input-group">
                           <input type="date" class="form-control" name="fecha" min="<?php echo $fecha_actual ?>" onchange="horas();">
                        </div>
                     </td>
                     <td width="10%" style="font-size:15px"><b>&nbsp;&nbsp;&nbsp;Hora entrega:</td>   
                     <td width="10%">   
                        <div class="input-group">
                           <select class="form-control" id="horasLista" name="hora">
                           </select>                
                        </div>
                     </td>    
                     <td width="8%" style="font-size:15px"><b>&nbsp;&nbsp;&nbsp;Estatus:</td>   
                     <td width="10%">
                        <div class="input-group">
                           <select class="form-control" name="estatus">
                              <option value="1">En proceso</option>
                              <option value="2">Confirmado</option>
                           </select>
                        </div>               
                     </td>
                  </tr>
               </table>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-11">
      <div class="panel-body">
         <table class="table table-bordered">
            <thead>
            <tr>
               <td width="70%">Nombre</td>
               <td width="7%">Cantidad</td>
               <td width="20%">Precio</td> 
               <td width="5%">Acción</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($prodsSeleccionados as $prod):?>
               <td width="70%"><?php echo $prod['name'] ?></td>
               <td width="7%"><input type='text' class="form-control" name='cantidad' value='<?php echo $prod["cantidad"] ?>' onChange="multiplicaEnt();"/></td>
               <td width="20%"><?php echo $prod['precio'] ?></td>
               <td class="text-center">
                  <div class="btn-group">
                     <a href="delProdReg.php?cveTemp=<?php echo (int)$prod['cve_temporal'];?>" class="btn btn-danger btn-xs" title="Eliminar" data-toggle="tooltip">
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
   <div class="col-md-12">
      <div class="panel panel-default">
         <div class="panel-heading clearfix">
            <div class="form-group">
               <table HEIGHT="70px" id="tab" align="right">
                  <tr>
                     <td width="5%" style="font-size:40px"><b>Total</td> 
                     <td width="70%" style="font-size:40px" align="right"><b><?php echo $total ?></td>
                  </tr>
               </table>
            </div>   
         </div>
      </div>
   </div>      
   <div class="col-md-12">
      <div class="panel-body">
         <table id="tab" align="center">
            <tr>
               <td width="20%" align="center"><input type="button" class="btn btn-primary" name="Registrar" value="Registrar" onClick="registrar();" /></td>
            </tr>
         </table>
      </div>
   </div>
   <input type="hidden" name="idProd" value="">
   <input type="hidden" name="precio" value="">
   <input type="hidden" name="multiplos" value="">
   <input type="hidden" name="user" value="<?php echo ucfirst($user['name']) ?>">
</div>
</form>
</body>
</html>
<?php include_once('../layouts/footer.php'); ?>
