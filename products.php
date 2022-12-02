<?php

session_start();
require "config.php";

if (!isset($_SESSION["login"])) {
  echo "<script>
          alert('Please login first as Member or Admin!');
          document.location.href = 'login.php';
      </script>";
}

// default
$login = $_SESSION["login"];
$mode = "read";
$products = $db->query("SELECT * FROM products");
$search = "all";

if (isset($_GET["mode"])) {
  $mode = $_GET["mode"];
}

if (isset($_GET["search"])) {
  $search = $_GET["search"];
}

if ($search == "all") {
  $products = $db->query(
    "SELECT * FROM products
    ORDER BY date_released"
  );
  
} else {
  $products = $db->query(
    "SELECT * FROM products
    WHERE name LIKE '%$search%'
    OR brand = '$search'
    OR color = '$search'
    ORDER BY date_released"
  );
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">
  <link rel="stylesheet" href="css/products.css?v=<?= time(); ?>">
  <link rel="shortcut icon" href="img/icons/logo.png" type="image/x-icon">

  <title> Products | Tarvita Cell </title>
</head>

<body>
  <div class="page-wrapper <?= $login; ?>">

    <?php include "header.php"; ?>

    <div class="main-content">

      <section class="wrapper searching">
        <h1><center>Products</center></h1> <br>
        <form action="">
          <input type="search" name="search" id="search" placeholder="Search" 
          class="form-input <?= $login == "member" ? "white" : "black" ?>">
          <button type="submit" name="on_search" class="btn <?= $login == "member" ? "black" : "white" ?>"> Search </button>
        </form>
        <p>Hasil pencarian: <?= $search; ?></p>
      </section>

      <section class="wrapper products">

        <?php while ($product = mysqli_fetch_array($products)) { ?>
        <a href="product.php?id=<?=$product["product_id"];?>">
        <div class="product <?= $login == "member" ? "white" : "black" ?>">

        <!------------------- MODE EDIT ------------------->
          <?php if ($mode == "edit") { ?>
            <div class="editing">
              <a href="add-product.php?mode=edit&id=<?= $product["product_id"]; ?>">
              <button class="btn white edit img">
                <img src="img/icons/edit.png" alt="" width="28">
              </button> </a>

              <a href="delete-product.php?id=<?= $product["product_id"]; ?>"
              onclick="return confirm('Are you sure you want to delete <?= $product['name']; ?>?')">
              <button class="btn white del img">
                <img src="img/icons/delete.png" alt="" width="28">
              </button> </a>
            </div>
          <?php } ?>
          
          <div class="img" style="background-image: url('img/products/<?= $product["photo"]; ?>')"></div>
          <div class="desc">
            <h2> <?= $product["name"]." (". ucfirst($product["color"]).")"; ?> </h2>
            <p> IDR <?= $product["price"]; ?> </p>
          </div>

        </div>
        </a>
        <?php } ?>

      </section>



    </div>

    <?php include "footer.php"; ?>

    <script src="js/style.js"></script>

  </div>

</body>

</html>