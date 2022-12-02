<?php

session_start();
require "config.php";

if (!isset($_SESSION["login"])) {
  echo "<script>
          alert('Please login first as Member!');
          document.location.href = 'login.php';
      </script>";
}

$username = $_SESSION["username"];

// unselect semua produk 
$result = $db->query(
  "UPDATE cart_products
  SET selected=0
  WHERE username='$username'"
);

$products = $db->query(
  "SELECT cpr.product_id, amount, selected, name, price, stock, photo, amount*price paid 
  FROM cart_products cpr
  LEFT JOIN products pr
  ON (cpr.product_id = pr.product_id)
  WHERE username='$username'"
);

// hapus dari keranjang
if (isset($_GET["del"])) {
  $id = $_GET["del"];

  $result = $db->query(
    "DELETE FROM cart_products
    WHERE username='$username' AND product_id='$id'"
  );

  header("Location: cart.php");
}

// tekan check out
if (isset($_POST["buy"])) {

  $i = 0; $j = 0;
  while ($i <= $_POST["n_product"]) {
    
    if (isset($_POST["product_id"][$i])) {
      $id = $_POST["product_id"][$i];
      
      // select produk dibeli
      $result = $db->query(
        "UPDATE cart_products
        SET selected=1
        WHERE username='$username' AND product_id='$id'"
      );
      $j++;
    }

    $i++;

  }
  
  if ($j != 0) {
    header("Location: order.php?buy=true");
  }


}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">
  <link rel="stylesheet" href="css/cart.css?v=<?= time(); ?>">
  <link rel="shortcut icon" href="img/icons/logo.png" type="image/x-icon">

  <title> Cart | Tarvita Cell </title>

</head>

<body>
  <div class="page-wrapper <?= $_SESSION["login"]; ?>">

    <?php include "header.php"; ?>

    <div class="main-content">
      <section class="wrapper">
        <h1>
          <center>Cart</center>
        </h1>
      </section>

      <form action="" method="POST">
        <section class="wrapper">
          <?php $i = 0; ?>
          <?php while ($product = mysqli_fetch_assoc($products)) { ?>
            <div class="product">

              <input type="checkbox" name="product_id[<?= $i++; ?>]" id="product-<?= $product["product_id"] ?>" 
              onchange="updatePaid()" 
              value="<?= $product["product_id"]; ?>">

              <input type="number" name="paid" value="<?= $product["paid"]; ?>" hidden>
              <input type="number" name="n_product" value="<?=$i;?>" hidden>

              <label for="product-<?= $product["product_id"] ?>">
                <div class="img" style="background-image: url('img/products/<?= $product["photo"]; ?>');"></div>
              
                  <div class="desc">
                    <a href="product.php?id=<?= $product["product_id"] ?>">
                      <h3> <?= $product["name"] ?></h3>
                      <p> IDR <?= $product["price"] ?></p>
                    </a>

                    <input type="number" name="amount" min="1" max="<?= $product["stock"] ?>" class="form-input white" value="<?= $product["amount"]; ?>" onkeyup="updateAmount(this, <?= $product['price'] ?>)" onchange="updateAmount(this, <?= $product['price'] ?>)">

                  </div>

                
              </label>
            
              <a href="?del=<?= $product["product_id"]; ?>" class="del img"></a>

            </div>
          <?php } ?>
        </section>

        <section class="wrapper">
          <p> Total price: Rp <span class="total-price"> 0 </span> </p>
          <button type="submit" name="buy" class="btn black">Check Out</button>
        </section>
      </form>

    </div>

    <?php include "footer.php"; ?>

    <script src="js/style.js?v=<?= time(); ?>"></script>

  </div>

</body>

</html>