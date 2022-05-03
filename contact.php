<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['send'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    $select_message = mysqli_query($conn, "SELECT * FROM `mensaje` WHERE nombre = '$name' AND email = '$email' AND numero = '$number' AND mensaje = '$msg'") or die('query failed');

    if(mysqli_num_rows($select_message) > 0){
        $message[] = 'Mensaje ya enviado!';
    }else{
        mysqli_query($conn, "INSERT INTO `mensaje`(user_id, nombre, email, numero, mensaje) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
        $message[] = 'Mensaje enviado!';
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title> Contacto</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Contactanos</h3>
    <p> <a href="home.php">Inicio</a> / contact </p>
</section>

<section class="contact">

    <form action="" method="POST">
        <h3>Envianos un mensaje!</h3>
        <input type="text" name="name" placeholder="Ingresa tu nombre" class="box" required> 
        <input type="email" name="email" placeholder="Ingresa tu email" class="box" required>
        <input type="number" name="number" placeholder="Ingresa tu  number" class="box" required>
        <textarea name="message" class="box" placeholder="Ingresa tu mensaje " required cols="30" rows="10"></textarea>
        <input type="submit" value="Enviar mensaje" name="send" class="btn">
    </form>

</section>






<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>