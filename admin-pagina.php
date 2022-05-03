<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">dashboard</h1>

   <div class="box-container">

      <div class="box">
         <?php
            $totales_pendientes = 0;
            $select_pendings = mysqli_query($conn, "SELECT * FROM `orden` WHERE payment_status = 'pending'") or die('query failed');
            while($fetch_pendings = mysqli_fetch_assoc($select_pendings)){
               $totales_pendientes += $fetch_pendings['precio_total'];
            };
         ?>
         <h3>$<?php echo $totales_pendientes; ?>/-</h3>
         <p>Total de pendientes</p>
      </div>

      <div class="box">
         <?php
            $total_completes = 0;
            $select_completes = mysqli_query($conn, "SELECT * FROM `orden` WHERE payment_status = 'completed'") or die('query failed');
            while($fetch_completes = mysqli_fetch_assoc($select_completes)){
               $total_completes += $fetch_completes['precio_total'];
            };
         ?>
         <h3>$<?php echo $total_completes; ?>/-</h3>
         <p>pagos completados
</p>
      </div>

      <div class="box">
         <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orden`") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
         ?>
         <h3><?php echo $number_of_orders; ?></h3>
         <p>pedidos realizados</p>
      </div>

      <div class="box">
         <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `productos`") or die('query failed');
            $number_of_products = mysqli_num_rows($select_products);
         ?>
         <h3><?php echo $number_of_products; ?></h3>
         <p>productos agregados</p>
      </div>

      <div class="box">
         <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `usuarios` WHERE tipo_de_usuario
             = 'user'") or die('query failed');
            $number_of_users = mysqli_num_rows($select_users);
         ?>
         <h3><?php echo $number_of_users; ?></h3>
         <p>Usuarios normales</p>
      </div>

      <div class="box">
         <?php
            $select_admin = mysqli_query($conn, "SELECT * FROM `usuarios` WHERE tipo_de_usuario = 'admin'") or die('query failed');
            $number_of_admin = mysqli_num_rows($select_admin);
         ?>
         <h3><?php echo $number_of_admin; ?></h3>
         <p>administradores</p>
      </div>

      <div class="box">
         <?php
            $select_account = mysqli_query($conn, "SELECT * FROM `usuarios`") or die('query failed');
            $number_of_account = mysqli_num_rows($select_account);
         ?>
         <h3><?php echo $number_of_account; ?></h3>
         <p>cuentas totales</p>
      </div>

      <div class="box">
         <?php
            $select_messages = mysqli_query($conn, "SELECT * FROM `mensaje`") or die('query failed');
            $number_of_messages = mysqli_num_rows($select_messages);
         ?>
         <h3><?php echo $number_of_messages; ?></h3>
         <p>nuevos mensajes</p>
      </div>

   </div>

</section>




<script src="js/admin_script.js"></script>

</body>
</html>