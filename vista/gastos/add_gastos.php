<?php
   $page_title = 'Agregar producto';
   require_once('../../modelo/load.php');
   // Checkin What level user has permission to view this page
   page_require_level(1);
   $all_categories = find_all('categories');
   $all_photo = find_all('media');
   $all_provedor = find_all('proveedor');  
   $all_sucursal = find_all('sucursal');
   $all_tipo_pagos = find_all('tipo_pago');
   $parametros = find_by_id('parametros','1');

   $user = current_user(); 
   $usuario = $user['id'];
   $idSucursal = $user['idSucursal'];
   ini_set('date.timezone','America/Mexico_City');
   $date=date('Y-m-d',time());
   $hora_actual=date('H:i:s',time());

   if(isset($_POST['add_gastos'])){
      $req_fields = array('gasto-proveedor',
                          'gasto-sucursal',
                          'categoria',
                          'forma',
                          'product-title',
                          'precioCompra',
                          'fecha');
      validate_fields($req_fields);
      if(empty($errors)){
         $p_name = remove_junk($db->escape($_POST['product-title']));
         $p_precioCompra = remove_junk($db->escape($_POST['precioCompra']));
         $p_proveedor = remove_junk($db->escape($_POST['gasto-proveedor']));
         $p_sucursal = remove_junk($db->escape($_POST['gasto-sucursal']));
         $p_forma = remove_junk($db->escape($_POST['forma']));
         $p_categoria = remove_junk($db->escape($_POST['categoria']));
         $p_fecha = remove_junk($db->escape($_POST['fecha']));
         $p_iva = remove_junk($db->escape($_POST['iva']));
         $p_total = remove_junk($db->escape($_POST['total']));
         $p_factura = remove_junk($db->escape($_POST['factura']));

         $respuesta = altaGasto($p_name,
                                $p_precioCompra,
                                $p_fecha,
                                $p_proveedor,
                                $p_sucursal,
                                $p_forma,
                                $p_categoria,
                                $p_iva,
                                $p_total,
                                $p_factura);

         if($respuesta){
            if ($p_forma == 1){
               $consMonto = buscaRegistroMaximo("caja","id");
               $montoActual=$consMonto['monto'];
               $idCaja = $consMonto['id'];

       	      $montoFinal = $montoActual - $p_total;

               if ($p_fecha == $date){
                  actCaja($montoFinal,$date,$idCaja);
                  altaHisEfectivo('11',
                                  $montoActual,
                                  $montoFinal,
                                  $idSucursal,
                                  $usuario,
                                  '',
                                  $date,
                                  $hora_actual);
               }
            }
            $session->msg('s',"Gasto agregado exitosamente. ");
            redirect('gastos.php', false);
         }else{
            $session->msg('d','Lo siento, Falló el registro.');
            redirect('add_gastos.php', false);
         }

      }else{
         $session->msg("d", $errors);
         redirect('add_gastos.php',false);
      }
  }
?>

<?php include_once('../layouts/header.php'); ?>
<script type="text/javascript" src="../../libs/js/general.js"></script>

<form name="form1" method="post" action="add_gastos.php">
<div class="row">
  <div class="col-md-7">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
   <div class="col-md-7">
      <div class="panel panel-default">
         <div class="panel-heading">
            <strong>
               <span class="glyphicon glyphicon-th"></span>
               <span>Agregar gasto</span>
               <img src="../../libs/imagenes/Logo.png" height="50" width="50" alt="" align="center">
            </strong>
         </div>
         <div class="panel-body">
            <div class="col-md-12">

               <div class="form-group">
                  <div class="row">
                     <div class="col-md-3">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-list-alt"></i>
                           </span>
                           <input type="text" class="form-control" name="factura" placeholder="Factura">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <select class="form-control" name="gasto-proveedor">
                        <option value="">Selecciona un proveedor</option>
                        <?php  foreach ($all_provedor as $id): ?>
                        <option value="<?php echo (int)$id['idProveedor'] ?>">
                        <?php echo $id['nom_proveedor'] ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <select class="form-control" name="gasto-sucursal">
                        <option value="">Selecciona una sucursal</option>
                        <?php  foreach ($all_sucursal as $idSuc): ?>
                        <option value="<?php echo (int)$idSuc['idSucursal'] ?>">
                        <?php echo $idSuc['nom_sucursal'] ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <select class="form-control" name="categoria">
                        <option value="">Selecciona una categoría</option>
                        <?php  foreach ($all_categories as $idCat): ?>
                        <option value="<?php echo (int)$idCat['id'] ?>">
                        <?php echo $idCat['name'] ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <select class="form-control" name="forma">
                        <option value="">Selecciona Forma de Pago</option>
                        <?php  foreach ($all_tipo_pagos as $id_pago): ?>
                        <option value="<?php echo (int)$id_pago['id_pago'] ?>">
                        <?php echo $id_pago['tipo_pago'] ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <div class="col-sm-3">
                        <input type="date" name="fecha">
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-th-large"></i>
                     </span>
                     <input type="text" class="form-control" name="product-title" placeholder="Descripción">
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col-md-2">
                        <span><strong>Subtotal</strong></span>
                     </div>
                     <div class="col-md-3">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-usd"></i>
                           </span>
                           <input type="number" step="0.01" min="1" class="form-control" name="precioCompra" onkeyup="asignar();">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col-md-2">
                        <span><strong>IVA <?php echo $parametros['iva'] ?> %</strong></span>
                     </div>
                  <div class="col-md-3">
                     <div class="input-group">
                        <span class="input-group-addon">
                           <i class="glyphicon glyphicon-usd"></i>
                        </span>
                        <input type="number" class="form-control" name="iva" value="0" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <input type="checkbox" name="aplicaIva" onclick="calculoIva();">
                     <span>Aplicar IVA</span>
                  </div>
               </div>
            </div>
            <div class="form-group">
               <div class="row">
                  <div class="col-md-2">
                     <span><strong>Total</strong></span>
                  </div>
                  <div class="col-md-3">
                     <div class="input-group">
                        <span class="input-group-addon">
                           <i class="glyphicon glyphicon-usd"></i>
                        </span>
                        <input type="number" class="form-control" name="total" value="0" readonly>
                     </div>
                  </div>
               </div>
            </div>
            <input type="hidden" name="porcIva" value="<?php echo $parametros['iva']; ?>">
            <button type="submit" name="add_gastos" class="btn btn-danger">Agregar gasto</button>
         </div>
      </div>
   </div>
</div>
</form>
<?php include_once('../layouts/footer.php'); ?>
