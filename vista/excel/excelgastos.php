<?php
	require_once('../../libs/Classes/PHPExcel.php');
   require_once('../../modelo/load.php');

   $factura = "";
  	$mes = "";
  	$anio = "";
  	$dia = "";

  	if(isset($_POST['factura']))
    	$factura =  remove_junk($db->escape($_POST['factura']));
  	if(isset($_POST['dia']))
    	$dia =  remove_junk($db->escape($_POST['dia']));
  	if(isset($_POST['mes']))
    	$mes =  remove_junk($db->escape($_POST['mes']));
  	if(isset($_POST['anio']))
    	$anio =  remove_junk($db->escape($_POST['anio']));

	if ($mes == "" && $anio == "" && $dia == ""){
     	$year = date('Y');           
     	$fechaInicial = $year."/01/01";
     	$fechaFinal = date('Y/m/d',time());
  	}
  	if ($mes == "" && $anio == "" && $dia != ""){
     	$year = date('Y');
     	$month = date('m');
     	$fechaInicial = $year."/".$month."/".$dia;
     	$fechaFinal = $year."/".$month."/".$dia;
  	}
  	if ($mes == "" && $anio != "" && $dia == ""){
     	$month = date('m');
     	$day = date('d');
     	$fechaInicial = $anio."/01/01";
     	$fechaFinal = $anio."/".$month."/".$day;
  	}
  	if ($mes == "" && $anio != "" && $dia != ""){
     	$month = date('m');
     	$fechaInicial = $anio."/".$month."/".$dia;
     	$fechaFinal = $anio."/".$month."/".$dia;
  	}
  	if ($mes != "" && $anio == "" && $dia == ""){
     	$year = date('Y');
     	$day = date('d');
     	$fechaInicial = $year."/".$mes."/01/";
     	$numDias = date('t', strtotime($fechaInicial));
     	$fechaFinal = $year."/".$mes."/".$numDias;
  	}
  	if ($mes != "" && $anio == "" && $dia != ""){
     	$year = date('Y');
     	$fechaInicial = $year."/".$mes."/".$dia;
     	$fechaFinal = $year."/".$mes."/".$dia;
  	}
  	if ($mes != "" && $anio != "" && $dia == ""){
     	$fechaInicial = $anio."/".$mes."/01";
     	$numDias = date('t', strtotime($fechaInicial));
     	$fechaFinal = $anio."/".$mes."/".$numDias;
  	}
  	if ($mes != "" && $anio != "" && $dia != ""){
     	$fechaInicial = $anio."/".$mes."/".$dia;
     	$fechaFinal = $anio."/".$mes."/".$dia;
  	}

  	$fechaIni = date('Y/m/d', strtotime($fechaInicial));
  	$fechaFin = date("Y/m/d", strtotime($fechaFinal));

  	if ($factura != ""){
     	$gastos = gastosFactura($factura,$fechaIni,$fechaFin);
  	}else{
     	$gastos = join_gastos_table2($fechaIni,$fechaFin);
  	}

  	// Titulo
   $titulo ='Lista de gastos';
   $longNomCat = strlen($titulo);
   if ($longNomCat >= 31)
      $titulo = substr($titulo,0,31);

   $objPHPExcel = new PHPExcel();

   $objPHPExcel->
      getProperties()
         ->setCreator("TEDnologia.com")
         ->setLastModifiedBy("TEDnologia.com")
         ->setTitle("Exportar Excel con PHP")
         ->setSubject("Documento de prueba")
         ->setDescription("Documento generado con PHPExcel")
         ->setKeywords("usuarios phpexcel")
         ->setCategory("reportes");

   $objPHPExcel->setActiveSheetIndex(0);
   $objPHPExcel->getActiveSheet()->setTitle("Lista de gastos");

   $objPHPExcel->getActiveSheet()->setCellValue('A1','FACTURA');
   $objPHPExcel->getActiveSheet()->setCellValue('B1','PROVEEDOR');
   $objPHPExcel->getActiveSheet()->setCellValue('C1','DESCRIPCIÓN');
   $objPHPExcel->getActiveSheet()->setCellValue('D1','CATEGORIA');
   $objPHPExcel->getActiveSheet()->setCellValue('E1','SUBTOTAL');
   $objPHPExcel->getActiveSheet()->setCellValue('F1','IVA');
   $objPHPExcel->getActiveSheet()->setCellValue('G1','TOTAL');
   $objPHPExcel->getActiveSheet()->setCellValue('H1','FORMA DE PAGO');
   $objPHPExcel->getActiveSheet()->setCellValue('I1','FECHA');

   $fila = 2;

   foreach ($gastos as $gasto) {
   	$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila,$gasto['factura']);
   	$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila,$gasto['nom_proveedor']);
   	$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila,$gasto['descripcion']);
   	$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila,$gasto['name']);
   	$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila,$gasto['monto']);
   	$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila,$gasto['iva']);
   	$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila,$gasto['total']);
   	$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila,$gasto['tipo_pago']);
   	$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila,$gasto['fecha']);

   	$objPHPExcel->getActiveSheet()->getStyle("E".$fila)->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
      $objPHPExcel->getActiveSheet()->getStyle("F".$fila)->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
      $objPHPExcel->getActiveSheet()->getStyle("G".$fila)->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");

 		$fila++; 
   }

   $objPHPExcel->getActiveSheet()->setTitle($titulo);
   $objPHPExcel->setActiveSheetIndex(0);

   $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
   $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
   $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
   $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
   $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
   $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
   $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
   $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
   $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

   header('Content-Type: application/vnd.ms-excel');
   header('Content-Disposition: attachment;filename="gastos.xls"'); //nombre del documento
   header('Cache-Control: max-age=0');
	
   $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
   $objWriter->save('php://output');
   exit;
?>