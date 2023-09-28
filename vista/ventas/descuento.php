<?php 
   $page_title = 'Descuento/Forma de pago';
   require_once  ('../../modelo/load.php');
   
   page_require_level(5);
   
   $vendedores =  find_all('users');
   $user =        current_user(); 
   $idCliente =   isset($_POST['idCliente']) ? $_POST['idCliente']:'';
   $parametros =  buscaRegistroPorCampo('parametros','id',"1");
   $porcIva =     $parametros['iva'];
   $usuario =     $user['name'];
   $usu =         $user['id'];
   $sucursal =    $user['idSucursal'];

   $nomCliente =  '';
   $venta =       0;
   $puntos =      0;
   $totaldesc =   0;

   $respSuma = sumaCampo('precio','temporal','usuario',$usuario);
   $total =    $respSuma['total'];      

   if ($idCliente != '') {

      $respPuntos =        obtenPuntos($idCliente);

      if ($respPuntos != null) {
         $puntos =         $respPuntos['venta'];
         $venta =          $respPuntos['venta'];
         $nom_clienteC =   $respPuntos['nom_cliente'];
      }
      if ($nomCliente == "") {
         $consCliente =    buscaRegistroPorCampo('cliente','idcredencial',$idCliente);
         if ($consCliente != null)
            $nomCliente =  $consCliente['nom_cliente'];
      }

      $consNumRegs =       cuentaRegsTemporal($usuario,$sucursal);
      
      if ($consNumRegs !=  null and $puntos >= 1)
         $totaldesc =      ((int)$puntos * 0.13) * $consNumRegs['numRegs'];
   }
?>
<script language="Javascript">
   function aplicar() {
      var sumaTotal = parseFloat(document.form1.sumaTotal.value) || 0;
      var pago =      parseFloat(document.form1.pago.value) || 0;
      var total =     document.form1.total.value;
      var totalConDesc = document.form1.totalConDesc.value;

      var cambio =    document.form1.cambio.value;
      var vendedor =  document.form1.vendedor.value;

      if (vendedor == ""){
         alert("Debe seleccionar a un vendedor.");
         document.form1.vendedor.focus();
         window.scrollTo(0,0);
         return -1;
      }
      if (pago == 0){
         alert("Debe Ingresar un monto a pagar.");
         return -1;
      }
      if (sumaTotal>pago) {
         alert("El monto ingresado es menor al total a pagar, faltan: $"+(sumaTotal-pago));
         return -1; 
      }
      if (total == '' || totalConDesc == '') {
         alert("No se puede realizar la venta");
         return -1;
      }
      if (sumaTotal<pago) {
         alert(vendedor+", recuerda dar $"+cambio+", de cambio en efectivo.");
      }

      document.form1.action = "ventas.php";
      document.form1.submit();
   }
   function regresa(){
      document.form1.action = "add_sale.php";
      document.form1.submit();
   }
   function sumaPago() {
      var efectivo =       parseFloat(document.form1.efectivo.value) || 0;
      var transferencia =  parseFloat(document.form1.transferencia.value) || 0;
      var deposito =       parseFloat(document.form1.deposito.value) || 0;
      var tarjeta =        parseFloat(document.form1.tarjeta.value) || 0;

      var totTarjeta =     tarjeta * 0.05;
      var sumaTotal =      efectivo + transferencia + deposito + tarjeta + totTarjeta;

      document.form1.tarjetaComision.value = totTarjeta.toFixed(2);
      document.form1.pago.value =            sumaTotal.toFixed(2);
   }
   function vuelto() {
      var pago =        parseFloat(document.form1.pago.value) || 0;
      var sumaTotal =   parseFloat(document.form1.sumaTotal.value) || 0;  
      var cambio =      0;
      
      cambio = (pago - sumaTotal).toFixed(2);

      if (cambio < 0)
         cambio = 0;

      document.form1.cambio.value = cambio;
   }
   function calculoIva() {
      var calcIva =        document.form1.porcIva.value/100;
      var hayDescuento =   document.form1.hayDescuento.value;
      var precio =         parseFloat(document.form1.totalConDesc.value);
      var subtotal =       0;

      if (hayDescuento == "0")
         subtotal = document.form1.total.value;
      else
         subtotal = document.form1.totalConDesc.value;

      if (document.form1.iva.checked)
         document.form1.porxIva.value = (subtotal * calcIva).toFixed(2);
      else
         document.form1.porxIva.value = parseFloat(0).toFixed(2);
   }
   function setPrecio() {
      var precio =            parseFloat(document.form1.totalConDesc.value);
      var tarjetaComision =   parseFloat(document.form1.tarjetaComision.value) || 0;

      if (document.form1.iva.checked) {
         var porxIva =  parseFloat(document.form1.porxIva.value);
         precio +=      porxIva;
      }

      precio += tarjetaComision;

      document.form1.sumaTotal.value = precio.toFixed(2);
   }document.addEventListener('DOMContentLoaded', function() {
      setPrecio();
   });
</script>

<?php include_once('../layouts/header.php'); ?>

<form name="form1" method="post" action="ventas.php">
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
                  <span>Descuento/Forma de Pago</span>
               </strong>
            </div>
            <div class="panel-body">
               <table class="table table-bordered">
                  <thead>
                     <tr>
                        <td width="50%">Cliente</td>
                        <td width="50%">Puntos</td>
                     </tr>
                     <tr>
                        <td width="50%"><?php echo $nomCliente ?></td>
                        <td width="50%"><?php echo floor($venta) ?></td>
                     </tr>
                  </thead>
               </table>
               <?php 
                  if ($puntos >= 1)
                     $totalfin = round($total - $totaldesc,2); 
                  else
                     $totalfin = $total;
               ?>
               <div class="input-group">
                  <div class="form">
                     <div class="form-group">
                        <div class="row">
                           <div class="col-md-6">
                              <span>Total de compra:</span>
                           </div>
                           <div class="col-md-5">
                              <input type="number" name="total" value="<?php echo $total ?>" readonly="readonly">
                           </div>
                        </div>
                        <br>
                        <div class="row">
                           <div class="col-md-6">
                              <span> Aplicando Descuento:</span>
                           </div>
                           <div class="col-md-5">
                              <input type="number" name="totalConDesc" value="<?php echo $totalfin ?>" readonly="readonly">
                           </div>
                        </div>
                     </div>
                     <input type="hidden" name="totaldes"      value="<?php echo $totaldesc ?>" readonly="readonly">
                     <input type="hidden" name="puntosdes"     value="<?php echo floor($puntos) ?>">
                     <input type="hidden" name="idCliente"     value="<?php echo $idCliente ?>">
                     <input type="hidden" name="user"          value="<?php echo $usuario ?>">
                     <input type="hidden" name="idSuc"         value="<?php echo $sucursal ?>">
                     <input type="hidden" name="idUsu"         value="<?php echo $usu ?>">
                     <input type="hidden" name="porcIva"       value="<?php echo $porcIva ?>">
                  </div>
               </div>
               <div class="input-group">
                  <div class="form">
                     <br>
                     <select class="form-control" name="vendedor">
                        <option value="">Selecciona vendedor</option>
                        <?php  foreach ($vendedores as $id): ?>
                           <option value="<?php echo $id['username'] ?>"><?php echo $id['name'] ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
               </div>
               <?php
                  if ($venta == 0) {?>
                     <div class="input-group">
                        <div class="form">
                           <br>                   
                           <select class="form-control" name="hayDescuento">
                              <option value="0">Sin descuento</option>
                           </select>
                        </div>   
                     </div>
               <?php } else { ?>
                  <div class="input-group">
                     <div class="form">
                        <br>
                        <select class="form-control" name="hayDescuento">
                           <option value="0">Sin descuento</option>
                           <option value="1">Con descuento</option>
                        </select>
                     </div>
                  </div>
               <?php } ?>
               <div class="input-group">
                  <div class="form">
                     <br>                   
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">           
                     <div class="col-md-10">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-usd"></i>
                           </span>
                           <input type="number" step="0.01" class="form-control" name="efectivo" placeholder="Efectivo" onkeyup="(()=>{sumaPago(); vuelto();})()">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">           
                     <div class="col-md-10">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-usd"></i>
                           </span>
                           <input type="number" step="0.01" class="form-control" name="transferencia" placeholder="Transferencia" onkeyup="(()=>{sumaPago(); vuelto();})()">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">           
                     <div class="col-md-10">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-usd"></i>
                           </span>
                           <input type="number" step="0.01" class="form-control" name="deposito" placeholder="Depósito" onkeyup="(()=>{sumaPago(); vuelto();})()">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">           
                     <div class="col-md-5">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-usd"></i>
                           </span>
                           <input type="number" step="0.01" class="form-control" name="tarjeta" placeholder="Tarjeta" onkeyup="(()=>{sumaPago(); setPrecio(); vuelto();})()">
                        </div>
                     </div>
                     <div class="col-md-5">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-usd"></i>
                           </span>
                           <input type="number" step="0.01" class="form-control" name="tarjetaComision" placeholder="Comisión + 5%" readonly>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col-md-5">
                        <label>IVA (+ <?php echo $porcIva ?> %)</label>
                        <input type="checkbox" id="iva" name="iva" onclick="(()=>{calculoIva(); setPrecio(); sumaPago(); vuelto();})()">
                        <label>:</label>
                     </div>
                     <div class="col-md-5">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-usd"></i>
                           </span>
                           <input type="number" step="0.01" class="form-control" name="porxIva" placeholder="IVA (16%)" readonly>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col-md-5">
                        <label>Total:</label>
                     </div>
                     <div class="col-md-5">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-usd"></i>
                           </span>
                           <input type="number" step="0.01" class="form-control" name="sumaTotal" placeholder="Total a cobrar" readonly>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-5">
                     <label>Pagó:</label>
                  </div>
                  <div class="col-md-5">
                     <label>Cambio:</label>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">           
                     <div class="col-md-5">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-usd"></i>
                           </span>
                           <input type="number" step="0.01" class="form-control" name="pago" placeholder="Pagó" readonly>
                        </div>
                     </div>
                     <div class="col-md-5">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="glyphicon glyphicon-usd"></i>
                           </span>
                           <input type="number" step="0.01" class="form-control" name="cambio" placeholder="Cambio" readonly>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <div class="row">
                     <div class="col-md-3">
                        <div class="input-group">
                           <input type="button" name="button" onclick="aplicar();" class="btn btn-primary" value="Realizar Venta">
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="input-group">
                           <a href="#" onclick="regresa();" class="btn btn-danger">Regresar</a>                    
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <br>
         </div>
      </div>
   </div>
</form>

<?php include_once('../layouts/footer.php'); ?>