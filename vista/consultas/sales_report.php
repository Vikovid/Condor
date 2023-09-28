<?php
  $page_title = 'Consulta de ventas';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $sucursales = find_all('sucursal');   
?>
<?php include_once('../layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
   <div class="col-md-6">
      <div class="panel">
         <div class="panel-heading">
         </div>
         <div class="panel-body">
         <form class="clearfix" method="post" action="sale_report_process.php">
            <div class="form-group">
               <label class="form-label">Rango de fechas</label>
               <div class="input-group">
                  <input type="text" class="datepicker form-control" name="fecha-inicial" placeholder="Desde">
                     <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                  <input type="text" class="datepicker form-control" name="fecha-final" placeholder="Hasta">
               </div>
               <div>
                  <select class="form-control" name="sucursal">
                     <option value="">Selecciona una sucursal</option>
                     <?php  foreach ($sucursales as $id): ?>
                     <option value="<?php echo (int)$id['idSucursal'] ?>">
                     <?php echo $id['nom_sucursal'] ?></option>
                     <?php endforeach; ?>
                  </select>
               </div>  
            </div>
            <div class="form-group">
               <button type="submit" name="submit" class="btn btn-primary">Generar Consulta</button>
            </div>
         </form>
         </div>
      </div>
   </div>
</div>
<?php include_once('../layouts/footer.php'); ?>
