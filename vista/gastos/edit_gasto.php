<?php
   $page_title = 'Editar gasto';
   require_once('../../modelo/load.php');
   // Checkin What level user has permission to view this page
   page_require_level(1);
   $user = current_user(); 
   $usuario = $user['id'];
   $idSucursal = $user['idSucursal'];
   ini_set('date.timezone','America/Mexico_City');
   $fecha_actual=date('Y-m-d',time());
   $hora_actual=date('H:i:s',time());

   $gastos = find_by_id('gastos',(int)$_GET['id']);
   $gastoActual = $gastos['total'];
   $proveedor = buscaRegistroPorCampo('proveedor','idProveedor',(int)$_GET['idProveedor']);
   $tipo_pago = buscaRegistroPorCampo('tipo_pago','id_pago',(int)$_GET['id_pago']);
   $all_provedor = find_all('proveedor');
   $all_categoria = find_all('categories');
   $tipos_pago = find_all('tipo_pago');
   $categoria = find_by_id('categories',(int)$_GET['idCategoria']);
   $parametros = find_by_id('parametros','1');

   if(!$gastos){
      $session->msg("d","Missing gasto id.");
      redirect('gastos.php');
   }

   if(isset($_POST['gastos'])){
      $req_fields = array('descripcion',
                          'precioCompra',
                          'idProveedor',
                          'idCategoria',
                          'idTipoPago' );

      validate_fields($req_fields);

      if(empty($errors)){
         $p_name  = remove_junk($db->escape($_POST['descripcion']));
         $p_precioCompra  = remove_junk($db->escape($_POST['precioCompra']));
         $p_prov  = remove_junk($db->escape($_POST['idProveedor']));
         $p_cat   = remove_junk($db->escape($_POST['idCategoria']));
         $p_tipoPago   = remove_junk($db->escape($_POST['idTipoPago']));
         $p_fecha   = remove_junk($db->escape($_POST['fecha']));
         $p_iva = remove_junk($db->escape($_POST['iva']));
         $p_total = remove_junk($db->escape($_POST['total']));
         $p_factura = remove_junk($db->escape($_POST['factura']));

         $respuesta = actGasto($p_name,
                               $p_precioCompra,
                               $p_prov,
                               $p_cat,
                               $p_tipoPago,
                               $p_fecha,
                               $p_iva,
                               $p_total,
                               $p_factura,
                               $gastos['id']);

         if($respuesta){
            if($p_tipoPago == 1){
               $consMonto = buscaRegistroMaximo("caja","id");
               $montoActual=$consMonto['monto'];
               $idCaja = $consMonto['id'];

               $totEfec = $gastoActual - $p_total;

               if ($totEfec > 0)
                  $mov = "12";
               else
                  $mov = "13";

               $montoFinal = $montoActual + $totEfec;

               $respCaja = actCaja($montoFinal,$fecha_actual,$idCaja);

               if($respCaja)
                  altaHisEfectivo($mov,
                                  $montoActual,
                                  $montoFinal,
                                  $idSucursal,
                                  $usuario,
                                  '',
                                  $fecha_actual,
                                  $hora_actual);
            }
            $session->msg('s',"Gasto ha sido actualizado. ");
            redirect('gastos.php?id='.$gastos['id'], false);
         }else{
            $session->msg('d','Lo siento, falló la actualización.');
            redirect('edit_gasto.php?id='.$gastos['id'].'&idProveedor='.$gastos['idProveedor'].'&id_pago='.$gastos['tipo_pago'].'&idCategoria='.$gastos['categoria'], false);
         }
      //aqui esta ok
      }else{
         $session->msg("d", $errors);
         redirect('edit_gasto.php?id='.$gastos['id'].'&idProveedor='.$gastos['idProveedor'].'&id_pago='.$gastos['tipo_pago'].'&idCategoria='.$gastos['categoria'], false);
      }
   }
?>

<?php include_once('../layouts/header.php'); ?>
<script type="text/javascript" src="../../libs/js/general.js"></script>

<div class="row">
   <div class="col-md-12">
      <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
   <div class="panel panel-default">
      <div class="panel-heading">
         <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Editar gasto</span>
         </strong>
      </div>
      <div class="panel-body">
         <div class="col-md-7">
            <form name="form1" method="post" action="edit_gasto.php?id=<?php echo (int)$gastos['id'] ?>&idProveedor=<?php echo $gastos['idProveedor'] ?>&id_pago=<?php echo $gastos['tipo_pago'] ?>&idCategoria=<?php echo $gastos['categoria'] ?>">

            <div class="form-group">
               <div class="input-group">
                  <span class="input-group-addon">
                     <i class="glyphicon glyphicon-list-alt"></i>
                  </span>
                  <input type="text" class="form-control" name="factura" value="<?php echo remove_junk($gastos['factura']);?>">
               </div>
            </div>
            <div class="form-group">
               <select class="form-control" name="idProveedor">
                  <option value="">Seleccione un proveedor</option>
                  <?php  foreach ($all_provedor as $prov): ?>
                  <option value="<?php echo (int)$prov['idProveedor']; ?>" <?php if($proveedor['idProveedor'] === $prov['idProveedor']): echo "selected"; endif; ?> >
                      <?php echo remove_junk($prov['nom_proveedor']); ?></option>
                  <?php endforeach; ?>
               </select>
            </div>
            <div class="form-group">
               <select class="form-control" name="idCategoria">
                  <option value="">Seleccione una categoría</option>
                  <?php  foreach ($all_categoria as $cat): ?>
                  <option value="<?php echo (int)$cat['id']; ?>" <?php if($categoria['id'] === $cat['id']): echo "selected"; endif; ?> >
                  <?php echo remove_junk($cat['name']); ?></option>
                  <?php endforeach; ?>
               </select>
            </div>
            <div class="form-group">
               <select class="form-control" name="idTipoPago">
                  <option value="">Seleccione una forma de pago</option>
                  <?php  foreach ($tipos_pago as $tipo): ?>
                  <option value="<?php echo (int)$tipo['id_pago']; ?>" <?php if($tipo_pago['id_pago'] === $tipo['id_pago']): echo "selected"; endif; ?> >
                  <?php echo remove_junk($tipo['tipo_pago']); ?></option>
                  <?php endforeach; ?>
               </select>
            </div>
            <div class="form-group">
               <div class="input-group">
                  <div class="col-sm-3">
                     <input type="date" name="fecha" value="<?php echo remove_junk($gastos['fecha']);?>">
                  </div>
               </div>
            </div>
            <div class="form-group">
               <div class="input-group">
                  <span class="input-group-addon">
                     <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="descripcion" value="<?php echo remove_junk($gastos['descripcion']);?>">
               </div>
            </div>
            <div class="form-group">
               <div class="row">
                  <div class="col-md-3">
                     <span><strong>Subtotal</strong></span>
                  </div>
                  <div class="col-md-3">
                     <div class="input-group">
                        <span class="input-group-addon">
                           <i class="glyphicon glyphicon-usd"></i>
                        </span>
                        <input type="number" step="0.01" min="1" class="form-control" name="precioCompra" value="<?php echo remove_junk($gastos['monto']);?>" onkeyup="asignar();">
                     </div>
                  </div>
               </div>
            </div>
            <div class="form-group">
               <div class="row">
                  <div class="col-md-3">
                     <span><strong>IVA <?php echo $parametros['iva'] ?> %</strong></span>
                  </div>
                  <div class="col-md-3">
                     <div class="input-group">
                        <span class="input-group-addon">
                           <i class="glyphicon glyphicon-usd"></i>
                        </span>
                        <input type="number" class="form-control" name="iva" value="<?php echo remove_junk($gastos['iva']);?>" readonly>
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
                  <div class="col-md-3">
                     <span><strong>Total</strong></span>
                  </div>
                  <div class="col-md-3">
                     <div class="input-group">
                        <span class="input-group-addon">
                           <i class="glyphicon glyphicon-usd"></i>
                        </span>
                        <input type="number" class="form-control" name="total" value="<?php echo remove_junk($gastos['total']);?>" readonly>
                     </div>
                  </div>
               </div>
            </div>
            <input type="hidden" name="porcIva" value="<?php echo $parametros['iva']; ?>">
            <button type="submit" name="gastos" class="btn btn-danger">Actualizar</button>            
            </form>            
         </div>
      </div>
   </div>
</div>
<?php include_once('../layouts/footer.php'); ?>