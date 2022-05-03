<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['order'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, 'flat no. '. $_POST['flat'].', '. $_POST['street'].', '. $_POST['city'].', '. $_POST['country'].' - '. $_POST['pin_code']);
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products[] = '';

    $cart_query = mysqli_query($conn, "SELECT * FROM `carro` WHERE user_id = '$user_id'") or die('query failed');
    if(mysqli_num_rows($cart_query) > 0){
        while($cart_item = mysqli_fetch_assoc($cart_query)){
            $cart_products[] = $cart_item['nombre'].' ('.$cart_item['quantity'].') ';
            $sub_total = ($cart_item['precio'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ',$cart_products);

    $order_query = mysqli_query($conn, "SELECT * FROM `ordenes` WHERE nombre = '$name' AND nummero = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND productos_totales = '$total_products' AND precio_total = '$cart_total'") or die('query failed');

    if($cart_total == 0){
        $message[] = 'TU carro esta vacio';
    }elseif(mysqli_num_rows($order_query) > 0){
        $message[] = 'pedido realizado ya!';
    }else{
        mysqli_query($conn, "INSERT INTO `ordenes`(user_id, nombre, numero, email, metodo, direccion, productos_totales, precio_total, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
        mysqli_query($conn, "DELETE FROM `carro` WHERE user_id = '$user_id'") or die('query failed');
        $message[] = 'pedido realizado correctamente!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>orden de pago</h3>
    <p> <a href="home.php">Inicio</a> / checkout </p>
</section>

<section class="display-order">
    <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `carro` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
    ?>    
    <p> <?php echo $fetch_cart['nombre'] ?> <span>(<?php echo '$'.$fetch_cart['precio'].'/-'.' x '.$fetch_cart['cantidad']  ?>)</span> </p>
    <?php
        }
        }else{
            echo '<p class="empty">Tu Carro esta vacio</p>';
        }
    ?>
    <div class="grand-total">grand total : <span>$<?php echo $grand_total; ?>/-</span></div>
</section>

<section class="checkout">

    <form action="" method="POST">

        <h3>place your order</h3>

        <div class="flex">
            <div class="inputBox">
                <span>tu nombre :</span>
                <input type="text" name="name" placeholder="enter your name">
            </div>
            <div class="inputBox">
                <span>tu numero:</span>
                <input type="number" name="number" min="0" placeholder="enter your number">
            </div>
            <div class="inputBox">
                <span>tu email :</span>
                <input type="email" name="email" placeholder="enter your email">
            </div>
            <div class="inputBox">
                <span>tu metodo de pago :</span>
                <select name="method">
                    <option value="cash on delivery">Pago en efectivo</option>
                    <option value="credit card">tarjeta de credito</option>
                    <option value="paypal">paypal</option>
                    <option value="paytm">paytm</option>
                </select>
            </div>
            <div class="inputBox">
                <span>direccion 01 :</span>
                <input type="text" name="flat" placeholder="e.g. flat no.">
            </div>
            <div class="inputBox">
                <span>direccion 02 :</span>
                <input type="text" name="street" placeholder="e.g.  streen name">
            </div>
            <div class="inputBox">
                <span>cuidad :</span>
                <input type="text" name="city" placeholder="e.g. mumbai">
            </div>
            <div class="inputBox">
                <span>estado :</span>
                <input type="text" name="state" placeholder="e.g. maharashtra">
            </div>
            <div class="inputBox">
                <span>pais :</span>
                <input type="text" name="country" placeholder="e.g. india">
            </div>
            <div class="inputBox">
                <span>pin code :</span>
                <input type="number" min="0" name="pin_code" placeholder="e.g. 123456">
            </div>
        </div>

        <input type="submit" name="order" value="order now" class="btn">

    </form>

</section>






<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>