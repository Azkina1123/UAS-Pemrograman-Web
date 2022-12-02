<?php

session_start();
require "config.php";

if (!isset($_SESSION["login"])) {
  echo "<script>
          alert('Please login first as Member or Admin!');
          document.location.href = 'login.php';
      </script>";
}

$login = $_SESSION["login"];

// tampilkan pesanan akun member saja
if ($login != "admin") {
  $username = $_SESSION["username"];
  $orders = $db->query(
    "SELECT * FROM orders
    WHERE username='$username'
    ORDER BY status DESC, datetime"
  );

// tampilkan semua pesanan
} else {
  $orders = $db->query(
    "SELECT *
    FROM orders
    ORDER BY status DESC, datetime"
  );
}

if (isset($_POST["update"])) {
  $order_id = $_POST["order_id"];
  if (update_status()) {
    echo "<script>
          alert('Successfully changed status on $order_id!');
          document.location.href = 'orders.php';
        </script>";
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
  <link rel="stylesheet" href="css/orders.css?v=<?= time(); ?>">
  <link rel="shortcut icon" href="img/icons/logo.png" type="image/x-icon">

  <title> Orders | Tarvita Cell </title>

</head>

<body>
  <div class="page-wrapper <?= $_SESSION["login"]; ?>">

    <?php include "header.php"; ?>

    <div class="main-content">
      <section class="wrapper">
        <h1> <center> Orders </center> </h1> <br>
        <div class="table">
        <table border="0" cellspacing="0">

          <!-- header -->
          <tr>
            <th <?= $login == "member" ? "style='color: white;'" : "" ?>> 
            No. </th>
            <th <?= $login == "member" ? "style='color: white;'" : "" ?>> 
            Date </th>
            <th <?= $login == "member" ? "style='color: white;'" : "" ?>> 
            Details </th>
            <th <?= $login == "member" ? "style='color: white;'" : "" ?>> 
            Status </th>
          </tr>

          <?php $no = 1; ?>
          <?php while ($order = mysqli_fetch_assoc($orders)) { ?>
            <tr>
              <td> <?= $no++; ?>. </td>
              <td> <?= $order["datetime"]; ?> </td>
              <td class="details">
                <a href="order.php?order_id=<?= $order["order_id"]; ?>">    
                  
                <?php
                $order_id = $order["order_id"];
                $product = $db->query(
                  "SELECT product_id, photo
                  FROM products RIGHT JOIN
                  order_products
                  USING (product_id)
                  WHERE order_id='$order_id'"
                );

                if (mysqli_num_rows($product) == 0) {
                  $unknown = true;
                } else {
                  $unknown = false;
                  $product = mysqli_fetch_assoc($product);
                }
                ?>
                
                <?php if ($unknown) { ?> 
                    <div class="img" 
                    style="background-image: url('img/icons/404-<?= $login == "member" ? "black" : "white" ?>.png');"></div>

                  <?php } else { ?>
                    <div class="img" style="background-image: url('img/products/<?= $product["photo"]; ?>');"></div>
                  <?php } ?>   

                  <div class="desc">
                    <h3> <?= $order["order_id"]; ?> </h3>
                    <p>Total paid: IDR <?= $order["total_paid"]; ?></p>
                  </div>
                </a>

              </td>
              
              <td>  

                <?php if ($login != "admin") { ?>
                  <?= $order["status"] ?>
                <?php } else { ?>
                  <form action="" method="POST">
                    <center>
                    <select name="status" class="form-input black">
                      <option value="On Packaging"
                      <?= $order["status"] == "On Packaging" ? "selected" : "" ?> >On Packaging</option>
                      <option value="Delivered"
                      <?= $order["status"] == "Delivered" ? "selected" : "" ?>>Delivered</option>
                    </select>

                    <input type="text" name="order_id" value="<?= $order["order_id"] ?>" hidden>

                    <button type="submit" name="update" class="btn white">OK</button>
                    </center>
                  </form>
                <?php } ?>

              </td>

            </tr>
          <?php } ?>

        </table>
        </div>

      </section>


    </div>

    <?php include "footer.php"; ?>

    <script src="js/style.js?v=<?= time(); ?>"></script>

  </div>

</body>

</html>