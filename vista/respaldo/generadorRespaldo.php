<?php
   require_once ('../../modelo/load.php');

   $dir = '../../libs/uploads/respaldos/';
   $archivo = '../../libs/uploads/respaldos/respaldoBD.txt';

   $cont = 0;
   $coma = ",";
   $columnas = "";
   $salto = "\r\n";
   $separador = "\t";
   
   $tablas = tablasBD(DB_NAME);

   if (file_exists($archivo))
   	  unlink($archivo);

   foreach ($tablas as $tabla) {
      $campos = camposTabla($tabla['nombre']);

      $registro = $tabla['nombre'].$salto.$salto;

      foreach ($campos as $campo) {
   	     $nomCampo = $campo['Field'];
   	     if ($cont == 0){
            $columnas = $campo['Field'];
            $registro = $registro.$campo['Field'];
   	     }else{
   	  	    $columnas = $columnas.$coma.$campo['Field'];
   	  	    $registro = $registro.$separador.$campo['Field'];
   	     }
   	     $cont++;
      }

      $registros = consultaCampos($columnas,$tabla['nombre']);

      if (!file_exists($dir)) {
         mkdir($dir, 0777, true);
      }

      reset($campos);

      $registro = $registro.$salto;
   
      foreach ($registros as $reg) {
   	     foreach ($campos as $campo) {
            $registro = $registro.$reg[$campo['Field']].$separador;
   	     }
   	     $registro = $registro.$salto;
      }

      $registro = $registro.$salto;

      $columnas = "";
      $cont = 0;

      $fp = fopen($archivo, 'a');
      fwrite($fp, $registro);   
      fclose($fp);
      chmod($archivo, 0777);
   }

   $nomArchivo = basename($archivo);
   $rutaArchivo = $dir.$nomArchivo;
   
   //Define Headers
   header("Cache-Control: public");
   header("Content-Description: FIle Transfer");
   header("Content-Disposition: attachment; filename=$nomArchivo");
   header("Content-Type: application/zip");
   header("Content-Transfer-Emcoding: binary");

   readfile($rutaArchivo);
   redirect('../consultas/admin.php',false);
?>