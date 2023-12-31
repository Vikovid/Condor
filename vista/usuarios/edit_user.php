<?php
  $page_title = 'Editar Usuario';
  require_once('../../modelo/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);

  $e_user = find_by_id('users',(int)$_GET['id']);
  $groups  = find_all('user_groups');
  $sucursales  = find_all('sucursal');

  if(!$e_user){
    $session->msg("d","Missing user id.");
    redirect('users.php');
  }

  //Update User basic info
  if(isset($_POST['update'])){
     $req_fields = array('name','username','level');
     validate_fields($req_fields);
     if(empty($errors)){
        $id = (int)$e_user['id'];
        $name = remove_junk($db->escape($_POST['name']));
        $username = remove_junk($db->escape($_POST['username']));
        $level = (int)$db->escape($_POST['level']);
        $status   = remove_junk($db->escape($_POST['status']));
        $sucursal = (int)$db->escape($_POST['sucursal']);       

        $result = actUsuario($name,$username,$level,$status,$sucursal,$id);
        
        if($result){
           $session->msg('s',"Cuenta actualizada ");
           redirect('edit_user.php?id='.(int)$e_user['id'], false);
        }else{
           $session->msg('d','Lo siento no se actualizaron los datos.');
           redirect('edit_user.php?id='.(int)$e_user['id'], false);
        }
     }else{
        $session->msg("d", $errors);
        redirect('edit_user.php?id='.(int)$e_user['id'],false);
     }
  }
?>
<?php
  // Update user password
  if(isset($_POST['update-pass'])){
     $req_fields = array('password');
     validate_fields($req_fields);
     if(empty($errors)){
        $id = (int)$e_user['id'];
        $password = remove_junk($db->escape($_POST['password']));
        $h_pass   = sha1($password);
        
        $result = actContrasenia($h_pass,$id);
        
        if($result){
          $session->msg('s',"Se ha actualizado la contraseña del usuario. ");
          redirect('edit_user.php?id='.(int)$e_user['id'], false);
        } else {
          $session->msg('d','No se pudo actualizar la contraseña de usuario.');
          redirect('edit_user.php?id='.(int)$e_user['id'], false);
        }
     }else{
        $session->msg("d", $errors);
        redirect('edit_user.php?id='.(int)$e_user['id'],false);
     }
  }
?>
<?php include_once('../layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
   <div class="col-md-6">
      <div class="panel panel-default">
         <div class="panel-heading">
            <strong>
               <span class="glyphicon glyphicon-th"></span>
                  Actualiza cuenta <?php echo remove_junk(ucwords($e_user['name'])); ?>
            </strong>
         </div>
         <div class="panel-body">
            <form method="post" action="edit_user.php?id=<?php echo (int)$e_user['id'];?>" class="clearfix">
               <div class="form-group">
                  <label for="name" class="control-label">Nombres</label>
                  <input type="name" class="form-control" name="name" value="<?php echo remove_junk(ucwords($e_user['name'])); ?>">
               </div>
               <div class="form-group">
                  <label for="username" class="control-label">Usuario</label>
                  <input type="text" class="form-control" name="username" value="<?php echo remove_junk(ucwords($e_user['username'])); ?>">
               </div>
               <div class="form-group">
                  <label for="level">Rol de usuario</label>
                  <select class="form-control" name="level">
                     <?php foreach ($groups as $group ):?>
                     <option <?php if($group['group_level'] === $e_user['user_level']) echo 'selected="selected"';?> value="<?php echo $group['group_level'];?>"><?php echo ucwords($group['group_name']);?></option>
                     <?php endforeach;?>
                  </select>
               </div>
               <div class="form-group">
                  <label for="level">Sucursal</label>
                  <select class="form-control" name="sucursal">
                     <?php foreach ($sucursales as $sucursales ):?>
                     <option <?php if($sucursales['idSucursal'] === $e_user['idSucursal']) echo 'selected="selected"';?> value="<?php echo $sucursales['idSucursal'];?>"><?php echo ucwords($sucursales['nom_sucursal']);?></option>
                     <?php endforeach;?>
                  </select>
               </div>
               <div class="form-group">
                  <label for="status">Estado</label>
                  <select class="form-control" name="status">
                     <option <?php if($e_user['status'] === '1') echo 'selected="selected"';?>value="1">Activo</option>
                     <option <?php if($e_user['status'] === '0') echo 'selected="selected"';?> value="0">Inactivo</option>
                  </select>
               </div>
               <div class="form-group clearfix">
                  <button type="submit" name="update" class="btn btn-info">Actualizar</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   <!-- Change password form -->
   <div class="col-md-6">
      <div class="panel panel-default">
         <div class="panel-heading">
            <strong>
               <span class="glyphicon glyphicon-th"></span>
               Cambiar <?php echo remove_junk(ucwords($e_user['name'])); ?> contraseña
            </strong>
         </div>
         <div class="panel-body">
            <form action="edit_user.php?id=<?php echo (int)$e_user['id'];?>" method="post" class="clearfix">
               <div class="form-group">
                  <label for="password" class="control-label">Contraseña</label>
                  <input type="password" class="form-control" name="password" placeholder="Ingresa la nueva contraseña" required>
               </div>
               <div class="form-group clearfix">
                  <button type="submit" name="update-pass" class="btn btn-danger pull-right">Cambiar</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<?php include_once('../layouts/footer.php'); ?>
