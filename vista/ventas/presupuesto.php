<?php
   require_once('../../modelo/load.php');

   $usuario= isset($_POST['user']) ? $_POST['user']:'';
   $idSucursal= isset($_POST['idSuc']) ? $_POST['idSuc']:'';
   $cliente = isset($_POST['idCliente']) ? $_POST['idCliente']:'';

   $nom_cliente = "";
   $dir_cliente = "";
   $tel_cliente = "";
   $total = 0;
?>
<?php
   include ('../../libs/fpdf/pdf2.php');

   ini_set('date.timezone','America/Mexico_City');
   $fecha=date('d/m/Y',time());

   $consCliente = buscaRegistroPorCampo('cliente','idcredencial',$cliente);

   if ($consCliente != null){
      $nom_cliente=$consCliente['nom_cliente'];
      $dir_cliente=$consCliente['dir_cliente'];
      $tel_cliente=$consCliente['tel_cliente'];
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
   $pdf->Text(40,75,utf8_decode('Av.Adolfo López Mateos R1, Mz.38 Lt.10 Col. Río de Luz'));
   $pdf->Text(23,82,utf8_decode('Ecatepec de Morelos, Méx. Tel:5588715568 Mail: glsoftst@hotmail.com'));
   $pdf->Cell(50,15,utf8_decode('Presupuesto:'),1,0,'C',1);
   $pdf->Cell(20,15,'Fecha: ',1,0,'C',1);
   $pdf->Cell(30,15,utf8_decode($fecha),1,0,'L',0);
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
   $pdf->SetFont('Arial','B',25);
   $pdf->Cell(10,15,utf8_decode('N'),1,0,'C',1);
   $pdf->Cell(70,15,'Producto',1,0,'C',1);
   $pdf->Cell(40,15,'Cantidad',1,0,'C',1);
   $pdf->Cell(40,15,'P. Unit.',1,0,'C',1);
   $pdf->Cell(40,15,'Precio',1,1,'C',1);
   $pdf->SetFont('Arial','',27);
   
   //set width for each column (6 columns)
   $pdf->SetWidths(Array(10,70,40,40,40));

   //set alignment
   $pdf->SetAligns(Array('C','L','R','R','R'));

   //set line height. This is the height of each lines, not rows.
   $pdf->SetLineHeight(11);

   //load json data
   foreach ($productosTicket as $prodTicket) {
      $prodTicket['PU'] = $prodTicket['precio']/$prodTicket['qty'];
      $pdf->Row(Array(
        $prodTicket['contador'],
        utf8_decode($prodTicket['name']),
        money_format('%.2n', $prodTicket['PU']),
        $prodTicket['qty'],
        money_format('%.2n',$prodTicket['precio']),
      ));
      $total = $total + $prodTicket['precio'];
   }

   $pdf->Cell(35,15,'Total',1,0,'C',1);
   $pdf->Cell(125,15,'',1,0,'C',1);
   $pdf->Cell(40,15,money_format('%.2n',$total),1,1,'R',1);

   $pdf->Cell(80,15,'                                          "Gracias por su Preferencia."',0,1,'C');
   $pdf->Cell(80,15,'                                             "GLSoftST"',0,1,'C');


   borraRegistroPorCampo('temporal','usuario',$usuario);

   $Name_PDF="presupuesto.pdf";
   $pdf->Output('D',$Name_PDF);
?>
