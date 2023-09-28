<?php
   $page_title = 'Lista de ventas';
   require_once('../../modelo/load.php');
   
   page_require_level(2);
   $user =        current_user(); 
   $nivel =       $user['user_level'];
   $encargados =  find_all('users');

   $meses = array(
      "01" => "Enero",
      "02" => "Febrero",
      "03" => "Marzo",
      "04" => "Abril",
      "05" => "Mayo",
      "06" => "Junio",
      "07" => "Julio",
      "08" => "Agosto",
      "09" => "Septiembre",
      "10" => "Octubre",
      "11" => "Noviembre",
      "12" => "Diciembre"
   );

   $c_idEncargado =  "";
   $mes =            "";
   $anio =           "";
   $nomCliente =     "";
   $ticketAnt =      0;

   if (isset($_POST['encargado']))
      $c_idEncargado =  remove_junk($db->escape($_POST['encargado']));
   if (isset($_POST['mes']))
      $mes =            remove_junk($db->escape($_POST['mes']));
   if (isset($_POST['anio']))
      $anio =           remove_junk($db->escape($_POST['anio']));

   if (empty($mes))
      $mes =   date('m');
   if (empty($anio))
      $anio =  date('Y');

   $fechaInicial =   $anio."/".$mes."/01";
   $numDias =        date('t', strtotime($fechaInicial));
   $fechaFinal =     $anio."/".$mes."/".$numDias;

   $fechaIni =       date('Y/m/d', strtotime($fechaInicial));
   $fechaFin =       date("Y/m/d", strtotime($fechaFinal));
   $fechIni =        date('d-m-Y', strtotime($fechaInicial));
   $fechFin =        date('d-m-Y', strtotime($fechaFinal));


   if ($c_idEncargado!="")
      $sales = venta3($c_idEncargado,$fechaIni,$fechaFin);
   else
      $sales = venta($fechaIni,$fechaFin);
?>

<?php include_once('../layouts/header.php'); ?>

<script type="text/javascript" src="../../libs/js/general.js"></script>

<body onload="focoEncargado();">
   <form name="form1" method="post" action="sales.php">
      <div class="row">
         <div class="col-md-6">
            <?php echo display_msg($msg); ?>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="panel panel-default">
               <div class="panel-heading clearfix">
                  <div class="form-group">
                     <div class="col-md-4">
                        <select class="form-control" name="encargado">
                           <option value="">Selecciona vendedor</option>
                           <?php  foreach ($encargados as $id): ?>
                              <option value="<?php echo $id['username'] ?>">
                                 <?php echo $id['name'] ?>
                              </option>
                           <?php endforeach; ?>
                        </select>
                     </div>      
                     <div class="col-md-2">
                        <select class="form-control" name="mes">
                           <option value="">Mes</option>
                           <?php foreach ($meses as $key => $value): ?>
                              <option value="<?php echo $key ?>"><?php echo $value ?></option>
                           <?php endforeach; ?>
                        </select>
                     </div>  
                     <div class="col-md-2">
                        <select class="form-control" name="anio">
                           <option value="">Año</option>
                           <?php $i = (int)2020; while ($i <= 2040):?>
                              <option value="<?php echo $i ?>"> <?php echo $i ?> </option>
                           <?php $i++; endwhile; ?>
                        </select>
                     </div>
                     <a href="#" onclick="ventaEncargado();" class="btn btn-primary">Buscar</a> 
                     <div class="pull-right">
                        <a href="add_sale.php" class="btn btn-primary">Agregar venta</a>
                        <img src="../../libs/imagenes/Logo.png" height="50" width="50" alt="" align="center">
                     </div>
                  </div>   
               </div>
               <div class="panel-body">
                  <table class="table table-bordered table-striped">
                     <thead>
                        <tr>
                           <th class="text-center" style="width: 7%;"> Vendedor</th>
                           <th class="text-center" style="width: 15%;">Cliente</th>
                           <th class="text-center" style="width: 39%;">Nombre del producto </th>
                           <th class="text-center" style="width: 5%;"> Cantidad</th>
                           <th class="text-center" style="width: 5%;"> Total </th>
                           <th class="text-center" style="width: 7%;"> Tipo Pago </th>
                           <th class="text-center" style="width: 10%;">Procedencia </th>
                           <th class="text-center" style="width: 6%;"> Fecha </th>
                           <?php if($nivel == "1"){ ?> 
                              <th class="text-center" style="width: 6%;"> Acciones </th>
                           <?php } ?>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($sales as $sale):?>
                        <?php 
                           $sqlNumPagos  =   "SELECT count(id_pago) AS numPagos,cantidad FROM pagos ";
                           $sqlNumPagos .=   "WHERE id_ticket = '{$sale['id_ticket']}'";
                           $respNumPagos =   $db->query($sqlNumPagos);
                           $consNumPagos =   mysqli_fetch_assoc($respNumPagos);
                           $numPagos =       $consNumPagos['numPagos'];
                           $abonoTotal =     $consNumPagos['cantidad'];

                           $cliente =        buscaRegistroPorCampo('cliente','idcredencial',$sale['idCliente']);
                           if ($cliente != null)
                              $nomCliente =  $cliente['nom_cliente'];

                           if ($numPagos == "1") {
                              $consTipoPago =   buscaRegistroPorCampo('pagos','id_ticket',$sale['id_ticket']);
                              $idTipoPago =     $consTipoPago['id_tipo'];

                              if ($idTipoPago == "1")
                                 $tipoPago = "Efectivo";
                              if ($idTipoPago == "2")
                                 $tipoPago = "Transferencia";
                              if ($idTipoPago == "3")
                                 $tipoPago = "Deposito";
                              if ($idTipoPago == "4")
                                 $tipoPago = "Tarjeta";
                           } else
                              $tipoPago = "Mixto";

                           $vendedor = $sale['vendedor'];
                          
                           $cantidad = $sale['qty'];
                           $fecha =    date("d-m-Y", strtotime ($sale['date']));

                           if ($sale['tipo_pago'] == "0") {
                              $producto = $sale['name'];
                              $precio =   $sale['price'];
                           }
                           if ($sale['tipo_pago'] != "0" && $ticketAnt != $sale['id_ticket']) {                 
                              $producto = "Abono crédito: ".$nomCliente;
                              $precio =   $abonoTotal;
                           }
                           if ($cliente != null) {
                              if ($cliente['user_level'] != "4")
                                 $nomCliente = "";
                           }
                        ?>
                        <?php if($sale['tipo_pago'] == "0") { ?>   
                        <tr>
                           <td><?php echo remove_junk($vendedor); ?></td>
                           <td><?php echo remove_junk($nomCliente); ?></td>
                           <td><?php echo utf8_decode(remove_junk($producto)); ?></td>
                           <td class="text-center"><?php echo $cantidad; ?></td>
                           <td class="text-right"><?php echo remove_junk($precio); ?></td>
                           <td class="text-center"><?php echo $tipoPago; ?></td>
                           <td class="text-center"><?php echo $sale['entrada']; ?></td>
                           <td class="text-center"><?php echo $fecha; ?></td>
                           <?php if($nivel == "1"){ ?>
                           <td class="text-center">
                              <div class="btn-group">
                                 <a href="consulta_sale.php?idTicket=<?php echo (int)$sale['id_ticket'];?>&vendedor=<?php echo $sale['vendedor'];?>&fecha=<?php echo $sale['date'];?>&cliente=<?php echo $nomCliente;?>&total=<?php echo "";?>" class="btn btn-primary btn-xs" title="Consultar" data-toggle="tooltip">
                                 <span class="glyphicon glyphicon-eye-open"></span>
                                 </a>
                                 <a href="edit_sale.php?id=<?php echo (int)$sale['id'];?>&vendedor=<?php echo $sale['vendedor'];?>&fecha=<?php echo $sale['date'];?>" class="btn btn-warning btn-xs" title="Editar" data-toggle="tooltip">
                                 <span class="glyphicon glyphicon-edit"></span>
                                 </a>
                                 <a href="delete_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-danger btn-xs" title="Eliminar" data-toggle="tooltip">
                                 <span class="glyphicon glyphicon-trash"></span>
                                 </a>
                              </div>
                           </td>
                           <?php } ?>
                           <?php if($nivel == "2"){ ?>
                           <td class="text-center">
                              <div class="btn-group">
                                 <a href="consulta_sale.php?idTicket=<?php echo (int)$sale['id_ticket'];?>&vendedor=<?php echo $sale['vendedor'];?>&fecha=<?php echo $sale['date'];?>&cliente=<?php echo $nomCliente;?>&total=<?php echo "";?>" class="btn btn-primary btn-xs" title="Consultar" data-toggle="tooltip">
                                 <span class="glyphicon glyphicon-eye-open"></span>
                                 </a>
                                 <a href="delete_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-danger btn-xs" title="Eliminar" data-toggle="tooltip">
                                 <span class="glyphicon glyphicon-trash"></span>
                                 </a>
                              </div>
                           </td>
                           <?php } ?>
                        </tr>
                        <?php } ?>                     
                        <?php if($sale['tipo_pago'] != "0" && $ticketAnt != $sale['id_ticket']) { ?>
                           <tr>
                              <td><?php echo remove_junk($vendedor);?></td>
                              <td><?php echo remove_junk($nomCliente);?></td>
                              <td><?php echo remove_junk($producto);?></td>
                              <td class="text-center"><?php echo $cantidad;?></td>
                              <td class="text-right"><?php echo remove_junk($precio);?></td>
                              <td class="text-center"><?php echo $tipoPago;?></td>
                              <td class="text-center"><?php echo $sale['entrada'];?></td>
                              <td class="text-center"><?php echo $fecha;?></td>
                              <?php if($nivel == "1") { ?>
                                 <td class="text-center">
                                    <div class="btn-group">
                                       <a href="consulta_sale.php?idTicket=<?php echo (int)$sale['id_ticket'];?>&vendedor=<?php echo $sale['vendedor'];?>&fecha=<?php echo $sale['date'];?>&cliente=<?php echo $nomCliente;?>&total=<?php echo $abonoTotal;?>" class="btn btn-primary btn-xs" title="Consultar" data-toggle="tooltip">
                                          <span class="glyphicon glyphicon-eye-open"></span>
                                       </a>
                                       <a href="../credito/deleteCredito.php?idTicket=<?php echo (int)$sale['id_ticket'];?>" class="btn btn-danger btn-xs" title="Eliminar" data-toggle="tooltip">
                                          <span class="glyphicon glyphicon-trash"></span>
                                       </a>                     
                                    </div>
                                 </td>
                              <?php } ?>
                           </tr>
                        <?php $ticketAnt = $sale['id_ticket']; ?>
                        <?php } ?>
                        <?php endforeach;?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </form>
</body>
<?php include_once('../layouts/footer.php'); ?>