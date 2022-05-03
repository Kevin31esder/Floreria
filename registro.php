<?php

@include 'config.php';

if(isset($_POST['submit'])){

   $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $name = mysqli_real_escape_string($conn, $filter_name);
   $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $email = mysqli_real_escape_string($conn, $filter_email);
   $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
   $pass = mysqli_real_escape_string($conn, md5($filter_pass));
   $filter_cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);
   $cpass = mysqli_real_escape_string($conn, md5($filter_cpass));

   $select_users = mysqli_query($conn, "SELECT * FROM `usuarios` WHERE email = '$email'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){
      $mensaje[] = 'user already exist!';
   }else{
      if($pass != $cpass){
         $mensaje[] = 'confirm password not matched!';
      }else{
         mysqli_query($conn, "INSERT INTO `usuarios`(nombre, email, password) VALUES('$name', '$email', '$pass')") or die('query failed');
         $mensaje[] = 'registered successfully!';
         header('location:login.php');
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registro</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php
if(isset($mensaje)){
   foreach($mensaje as $mensaje){
      echo '
      <div class="mensaje">
         <span>'.$mensaje.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   
<section class="form-container">

   <form action="" method="post">
      <h3>Registrarse</h3>
      <input type="text" name="name" class="box" placeholder="Ingresa tu username" required>
      <input type="email" name="email" class="box" placeholder="Ingresa tu email" required>
      <input type="password" name="pass" class="box" placeholder="Ingresa tu  password" required>
      <input type="password" name="cpass" class="box" placeholder="confirma tu contraseña" required>
      <input type="submit" class="btn" name="submit" value="Registrarse">
      <p>Ya tienes una cuenta <a href="login.php">Login</a></p>
   </form>

</section>

</body>
</html>