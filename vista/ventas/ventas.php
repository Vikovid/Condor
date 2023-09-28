<?php
   require_once('../../modelo/load.php');

   ini_set('date.timezone','America/Mexico_City');
   $fecha_actual =   date('Y-m-d',time());
   $hora_actual =    date('H:i:s',time());

   $usuario =        isset($_POST['user']) ?             $_POST['user']:'';
   $idUsuario =      isset($_POST['idUsu']) ?            $_POST['idUsu']:'';
   $idSucursal =     isset($_POST['idSuc']) ?            $_POST['idSuc']:'';
   $vendedor =       isset($_POST['vendedor']) ?         $_POST['vendedor']:'';
   $cliente =        isset($_POST['idCliente']) ?        $_POST['idCliente']:'';

   $puntosDes =      isset($_POST['puntosdes']) ?        $_POST['puntosdes']:'';
   $hayDescuento =   isset($_POST['hayDescuento']) ?     $_POST['hayDescuento']:'';

   $total =          isset($_POST['total']) ?            $_POST['total']:'';
   $totalConDesc =   isset($_POST['totalConDesc']) ?     $_POST['totalConDesc']:'';
   $totalDesc =      isset($_POST['totaldes']) ?         $_POST['totaldes']:'';

   $aplicarIva =     isset($_POST['iva']) ?              $_POST['iva']:'';

   $efectivo =       isset($_POST['efectivo']) ?         floatval($_POST['efectivo']) : 0;
   $transferencia =  isset($_POST['transferencia']) ?    floatval($_POST['transferencia']) : 0;
   $deposito =       isset($_POST['deposito']) ?         floatval($_POST['deposito']) : 0;
   $tarjeta =        isset($_POST['tarjeta']) ?          floatval($_POST['tarjeta']) : 0;
   
   $cambio =         isset($_POST['cambio']) ?           floatval($_POST['cambio']) : 0;

   $efectivo -= $cambio;

   $comision =       isset($_POST['tarjetaComision']) ?  floatval($_POST['tarjetaComision']) : 0;
   $porxIva =        isset($_POST['porxIva']) ?          floatval($_POST['porxIva']) : 0;

   $cont =           0;
   $cantNeg =        0;
   $descPorc =       0;

   $id_Ticket =      "";
   $nom_cliente =    "";
   $dir_cliente =    "";
   $tel_cliente =    "";

   $parametros =     find_by_id('parametros','1');
   $porcDescuento =  $parametros['porcDescuento'];

   $consTicket =     buscaRegistroMaximo('pagos','id_ticket');
   $id_ticket =      $consTicket['id_ticket'];
   if ($id_Ticket == "") {
      $consTicket =  buscaRegistroMaximo('sales','id_ticket');
      $id_ticket =   $consTicket['id_ticket'] + 1;
   } else
      $id_ticket =   $id_ticket + 1;

   $consIdSales =    buscaRegistroMaximo('sales','id');
   $id_sales =       $consIdSales['id'];
   if ($id_sales != "")
      $id_sales =    $id_sales + 1;

   $productos =      buscaProductosVentas($usuario,$idSucursal);
   $productos2 =     buscaProductosVentas($usuario,$idSucursal);

   foreach ($productos as $producto):
      $cantTemp = $producto['qty'];
      $cantProd = $producto['quantity'];
      $prodName = $producto['name'];

      $resta = $cantProd - $cantTemp;

      if ($resta < 0) {
         $cantNeg++;
         break;
      }
   endforeach;

   if ($cantNeg == 0) {
      foreach ($productos2 as $producto2):
         $id =          $id_sales;
         $idProducto=   $producto2['product_id'];
         $cantTemp =    $producto2['qty'];
         $precio =      $producto2['precio'];
         $usuTemp =     $producto2['usuario'];
         $sucTemp =     $producto2['idSucursal'];
         $cantProd =    $producto2['quantity'];
         $prodName =    $producto2['name'];
         $pCompra =     $producto2['pCompra'];

         $resta =       $cantProd - $cantTemp;

         if ($hayDescuento == "1") {
            $descPuntos =  floor($puntosDes) * ($porcDescuento/100);
            $precioVenta = $precio - $descPuntos;
         } else {
            $precioVenta = $precio;
            $totalDesc =   0;
         }
         if ($aplicarIva)  
            $resultado = altaVenta($id,
                                   $idProducto,
                                   $cantTemp,
                                   $precioVenta,
                                   $fecha_actual,
                                   $usuTemp,
                                   $sucTemp,
                                   $vendedor,
                                   $cliente,
                                   '0',
                                   '0',
                                   $id_ticket,
                                   '0',
                                   $precio,
                                   $pCompra,
                                   ($precio*1.16));
         else
            $resultado = altaVenta($id,
                                   $idProducto,
                                   $cantTemp,
                                   $precioVenta,
                                   $fecha_actual,
                                   $usuTemp,
                                   $sucTemp,
                                   $vendedor,
                                   $cliente,
                                   '0',
                                   '0',
                                   $id_ticket,
                                   '0',
                                   $precio,
                                   $pCompra,
                                   0);
         if ($resultado) {
            $resultado4 = actProdsVentas($resta,
                                         $fecha_actual,
                                         $idProducto,
                                         $idSucursal);
            if ($resultado4) {
               altaHistorico ('3',
                              $idProducto,
                              $cantProd,
                              $resta,
                              'Venta',
                              $idSucursal,
                              $idUsuario,
                              $vendedor,
                              $fecha_actual,
                              $hora_actual);

               altaTicket ($id_ticket,
                           $prodName,
                           $precio,
                           $cantTemp,
                           $totalDesc,
                           $descPorc,
                           $id);
               
               $consCliente = buscaRegistroPorCampo('cliente','idcredencial',$cliente);

               if ($consCliente != null)
                  actRegistroPorCampo('cliente','lista','1','idcredencial',$cliente);
            }
         }
         $cont++;
         $id_sales++;
      endforeach;
    
      if ($hayDescuento == "1" && $productos)
         actDescuentos($cliente);
   }
   if ($cont > 0 && $cantNeg == 0) {
      if ($efectivo != "") {
         $consMonto =   buscaRegistroMaximo("caja","id");
         $montoActual = $consMonto['monto'];
         $idCaja =      $consMonto['id'];
         $montoFinal =  $montoActual + $efectivo;

         altaPago($id_ticket,
                  $efectivo,
                  '1',
                  $fecha_actual,
                  $sucTemp,
                  '0');
         actCaja($montoFinal,$fecha_actual,$idCaja);

         registrarEfectivo('5',
                           $montoActual,
                           $montoFinal,
                           $idSucursal,
                           $idUsuario,
                           $vendedor,
                           $fecha_actual,
                           $hora_actual);
      }
      if ($transferencia != "")
         altaPago($id_ticket,
                  $transferencia,
                  '2',
                  $fecha_actual,
                  $sucTemp,
                  '0');
      
      if ($deposito != "")
         altaPago($id_ticket,
                  $deposito,
                  '3',
                  $fecha_actual,
                  $sucTemp,
                  '0');
      
      if ($tarjeta != "")
         altaPago($id_ticket,
                  $tarjeta,
                  '4',
                  $fecha_actual,
                  $sucTemp,
                  '0');
   }
?>
<?php
   include ('../../libs/fpdf/pdf2.php');

   if ($cont > 0 && $cantNeg == 0) {
      altaFolio($id_ticket);

      $consFolio = buscaRegistroMaximo('folio','id_folio');
      $folio =     $consFolio['id_folio'];

      ini_set('date.timezone','America/Mexico_City');
      $fecha = date('d/m/Y',time());

      $consCliente = buscaRegistroPorCampo('cliente','idcredencial',$cliente);

      if ($consCliente != null) {
         $nom_cliente = $consCliente['nom_cliente'];
         $dir_cliente = $consCliente['dir_cliente'];
         $tel_cliente = $consCliente['tel_cliente'];
      }

      $productosTicket = buscaProdsTicket($usuario,$idSucursal);

      $pdf=new PDF2($orientation='P',$unit='mm');
      $pdf->AddPage('portrait','legal');
      $pdf->SetMargins(10, 10, 10,10);
      $pdf->SetAutoPageBreak(true,5); 
      $pdf->SetFont('Arial','B',96);
      $pdf->Cell(80,15,'             GLSoftST',0,1,'C');
      $pdf->Cell(80,15,'    ',0,1,'C');
      $pdf->SetFont('Arial','B',50);
      $pdf->Cell(85,15,utf8_decode('                         Servicios Tecnológicos  '),0,1,'C');
      $pdf->Cell(80,15,'    ',0,1,'C');
      $pdf->Cell(80,15,'    ',0,1,'C');
      $pdf->SetFillColor(232,232,232);
      $pdf->SetFont('Arial','B',15);
      $pdf->Cell(80,8,'  ',0,1,'C');
      $pdf->Text(40,75,utf8_decode('Av.Adolfo López Mateos R1, Mz.36 Lt.10 Col. Río de Luz'));
      $pdf->Text(23,82,utf8_decode('Ecatepec de Morelos, Méx. Tel:5588715568 Mail: glsoftst@hotmail.com'));
      $pdf->Cell(90,15,'',0,0,'C',0);
      $pdf->Cell(30,15,utf8_decode('Remisión:'),1,0,'C',1);
      $pdf->Cell(30,15,utf8_decode($folio),1,0,'C',0);
      $pdf->Cell(20,15,'Fecha: ',1,0,'C',1);
      $pdf->Cell(30,15,utf8_decode($fecha),1,0,'C',0);
      $pdf->Cell(80,15,'  ',0,1,'C');
      $pdf->Cell(50,15,'Cliente',1,0,'C',1);
      if ($nom_cliente != ""){
         $pdf->CellFitScale(150,15,utf8_decode($nom_cliente),1,0,'L',1);
      }else{
         $pdf->Cell(150,15,utf8_decode($nom_cliente),1,0,'L',1);   
      }
      $pdf->Cell(80,15,'  ',0,1,'C');
      $pdf->Cell(50,15,utf8_decode('Dirección'),1,0,'C',1);
      if ($dir_cliente != ""){
         $pdf->CellFitScale(150,15,utf8_decode($dir_cliente),1,0,'L',1);
      }else{
         $pdf->Cell(150,15,utf8_decode($dir_cliente),1,0,'L',1);
      }
      $pdf->Cell(80,15,'  ',0,1,'C');
      $pdf->Cell(50,15,utf8_decode('Teléfono'),1,0,'C',1);
      $pdf->Cell(150,15,utf8_decode($tel_cliente),1,0,'L',1);
      $pdf->Cell(80,15,'  ',0,1,'C');
      $pdf->Cell(80,15,'  ',0,1,'C');
      $pdf->SetFillColor(232,232,232);
      $pdf->SetFont('Arial','B',27);
      $pdf->Cell(10,15,utf8_decode('N'),1,0,'C',1);
      $pdf->Cell(100,15,'Producto',1,0,'C',1);
      $pdf->Cell(50,15,'Cantidad',1,0,'C',1);
      $pdf->Cell(40,15,'Precio',1,1,'C',1);
      $pdf->SetFont('Arial','',27);
      $item=0;
     
      //set width for each column (6 columns)
      $pdf->SetWidths(Array(10,100,50,40));

      //set alignment
      $pdf->SetAligns(Array('C','L','C','R'));

      //set line height. This is the height of each lines, not rows.
      $pdf->SetLineHeight(11);

      //load json data
      foreach ($productosTicket as $prodTicket) {
         //$row['PU'] = $row['precio']/$row['qty'];
         $pdf->Row(Array(
           $prodTicket['contador'],
           utf8_decode($prodTicket['name']),
           //money_format('%.2n', $row['PU']),
           $prodTicket['qty'],
           $prodTicket['precio'],
         ));
      }

      $pdf->Cell(60,15,'Subtotal',1,0,'C',1);
      $pdf->Cell(100,15,'',1,0,'C',1);
      $pdf->Cell(40,15,money_format('%.2n',$total),1,1,'R',1);

      if ($hayDescuento == "0"){
         $puntosDes = "";
      }else{
         $pdf->CellFitScale(35,15,utf8_decode('Descuento'),1,0,'L',1);
         $pdf->Cell(125,15,'',1,0,'C',1);
         $pdf->Cell(40,15,'- '.money_format('%.2n',$totalDesc),1,1,'R',1);
      }

      if ($hayDescuento == 1)
         $totalcondescuento = $totalConDesc;
      else
         $totalcondescuento = $total;

      if ($tarjeta != ""){
         $totalcondescuento += $comision;
         $pdf->CellFitScale(60,15,utf8_decode('Comisión (+ 5%)'),1,0,'L',1);
         $pdf->Cell(100,15,'',1,0,'C',1);
         $pdf->Cell(40,15,money_format('%.2n',$comision),1,1,'R',1);
      }
      if ($aplicarIva) {
         $totalcondescuento += $porxIva;
         $pdf->CellFitScale(60,15,utf8_decode('IVA (+ 16%)'),1,0,'C',1);
         $pdf->Cell(100,15,'',1,0,'C',1);
         $pdf->Cell(40,15,money_format('%.2n',$porxIva),1,1,'R',1);
      }

      $pdf->Cell(60,15,'Total',1,0,'C',1);
      $pdf->Cell(100,15,'',1,0,'C',1);
      $pdf->Cell(40,15,money_format('%.2n',$totalcondescuento),1,1,'R',1);
      $pdf->SetFont('Arial','B',25);
      $pdf->Cell(160,18,'Tipo de pago',1,0,'C',1);
      $pdf->Cell(40,18,'',1,1,'C',1);

      $pdf->SetFont('Arial','',25);
      
      if($efectivo!=""){
        $pdf->Cell(160,18,'Efectivo',1,0,'C',0);
        $pdf->Cell(40,18,money_format('%.2n',$efectivo),1,1,'R',0);
      }
      if($transferencia!=""){
        $pdf->Cell(160,18,'Transferencia',1,0,'C',0);
        $pdf->Cell(40,18,money_format('%.2n',$transferencia),1,1,'R',0);  
      }
      if($deposito!=""){
        $pdf->Cell(160,18,utf8_decode('Depósito'),1,0,'C',0);
        $pdf->Cell(40,18,money_format('%.2n',$deposito),1,1,'R',0);
      }
      if($tarjeta!=""){
        $pdf->Cell(160,18,'Tarjeta',1,0,'C',0);
        $pdf->Cell(40,18,money_format('%.2n',($tarjeta + $comision)),1,1,'R',0);
      }
      
      $pdf->Cell(160,18,'Total',1,0,'C',1);
      $pdf->Cell(40,18,money_format('%.2n',$totalcondescuento),1,1,'R',1);

      $pdf->Cell(80,15,'                                          "Gracias por su Preferencia."',0,1,'C');
      $pdf->Cell(80,15,'                                             "GLSoftST"',0,1,'C');
     
      borraRegistroPorCampo('temporal','usuario',$usuario);

      $Name_PDF = "ticket_".$folio.".pdf";
      $pdf->Output('D',$Name_PDF);
   }
   if ($cont == 0 && $cantNeg == 0){
      echo "<script> alert('Venta ya realizada.');</script>";
      echo '<script> window.location="add_sale.php";</script>';   
   }
   if ($cantNeg > 0){
      echo "<script> alert('El producto: ' + '".$prodName."' + ' ya no tiene stock');</script>";
      echo '<script> window.location="add_sale.php";</script>';   
   }
?>