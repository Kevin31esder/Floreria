<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `carro` WHERE id = '$delete_id'") or die('query failed');
    header('location:cart.php');
}

if(isset($_GET['delete_all'])){
    mysqli_query($conn, "DELETE FROM `carro` WHERE user_id = '$user_id'") or die('query failed');
    header('location:cart.php');
};

if(isset($_POST['update_quantity'])){
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];
    mysqli_query($conn, "UPDATE `carro` SET cantidad = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
    $message[] = 'Cantidad del carro subida';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Carro de compras</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Carro de compras</h3>
    <p> <a href="home.php">Inicio</a> / cart </p>
</section>

<section class="shopping-cart">

    <h1 class="title">productos agregados</h1>

    <div class="box-container">

    <?php
        $total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `carro` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
    ?>
    <div  class="box">
        <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('Eliminar del carro?');"></a>
        <a href="view_page.php?pid=<?php echo $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
        <img src="uploaded_img/<?php echo $fetch_cart['imagen']; ?>" alt="" class="image">
        <div class="name"><?php echo $fetch_cart['nombre']; ?></div>
        <div class="price">$<?php echo $fetch_cart['precio']; ?>/-</div>
        <form action="" method="post">
            <input type="hidden" value="<?php echo $fetch_cart['id']; ?>" name="cart_id">
            <input type="number" min="1" value="<?php echo $fetch_cart['cantidad']; ?>" name="cart_quantity" class="qty">
            <input type="submit" value="update" class="option-btn" name="update_quantity">
        </form>
        <div class="sub-total"> sub-total : <span>$<?php echo $sub_total = ($fetch_cart['precio'] * $fetch_cart['cantidad']); ?>/-</span> </div>
    </div>
    <?php
    $total += $sub_total;
        }
    }else{
        echo '<p class="empty">Tu carro esta vacio</p>';
    }
    ?>
    </div>

    <div class="more-btn">
        <a href="cart.php?delete_all" class="delete-btn <?php echo ($total > 1)?'':'disabled' ?>" onclick="return confirm('Eliminar todos del carro?');">delete all</a>
    </div>

    <div class="cart-total">
        <p>total : <span>$<?php echo $total; ?>/-</span></p>
        <a href="shop.php" class="option-btn">continuar comprando</a>
        <a href="checkout.php" class="btn  <?php echo ($total > 1)?'':'disabled' ?>">proceder al checkout</a>
    </div>

</section>






<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>