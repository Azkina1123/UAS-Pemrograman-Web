<?php

session_start();
require "config.php";

if (!isset($_SESSION["login"])) {
  echo "<script>
          alert('Please login first as Member atau Admin!');
          document.location.href = 'login.php';
      </script>";
}

$login = $_SESSION["login"];
$mode = "read";

// mode member
if ($login == "member") {

  // jika member baru ingin membeli
  if (isset($_GET["buy"]) && $_GET["buy"] == "true") {
    $mode = "buy";
    $username = $_SESSION["username"];

    $result = $db->query(
      "SELECT cpr.username, us.address, cpr.amount, pr.product_id, pr.name, pr.price, pr.photo
      FROM users us
      LEFT JOIN cart_products cpr
      ON (us.username = cpr.username)
      LEFT JOIN products pr
      ON (cpr.product_id = pr.product_id)
      WHERE cpr.username='$username' AND selected=1"
    );

  } else {

    $id = $_GET["order_id"];

    $result = $db->query(
      "SELECT *
      FROM orders 
      LEFT JOIN order_products
      USING (order_id)
      LEFT JOIN products
      USING (product_id)
      WHERE order_id='$id'"
    );
    
  }

  if (isset($_POST["buy"])) {
    if (order_product()) {

      // delete dari keranjang
      $result = $db->query(
        "DELETE FROM cart_products
        WHERE username='$username' AND selected=1"
      );
      
      echo "<script>
            document.location.href = 'success.php'
          </script>";
    }
  }

// mode admin
} else {

  $id = $_GET["order_id"];

  $result = $db->query(
    "SELECT *
      FROM orders 
      LEFT JOIN order_products
      USING (order_id)
      LEFT JOIN products
      USING (product_id)
      WHERE order_id='$id'"
  ); 
}

$orders = [];
while ($order = mysqli_fetch_assoc($result)) {
  $orders[] = $order;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">
  <link rel="stylesheet" href="css/order.css?v=<?= time(); ?>">
  <link rel="shortcut icon" href="img/icons/logo.png" type="image/x-icon">

  <title> Purchase Form | Tarvita Cell </title>
</head>

<body>
  <div class="page-wrapper <?= $login; ?>">

    <?php include "header.php"; ?>

    <div class="main-content">

      <section class="wrapper">
        <h1> <center> Purchase Form </center> </h1>
      </section>

      <form action="" method="POST">

        <section class="wrapper">
          <?php if ($mode == "read") { ?>
            <p> ORDER ID: <b><?= $orders[0]["order_id"]; ?> (<?= $orders[0]["status"] ?>)</b> </p>
          <?php } ?>
          
          <?php $total_paid = 0; ?>
          <?php foreach ($orders as $order) { ?>
          <div class="product">

            <?php if ($order["photo"] == null) { ?>
              <div class="img" style="background-image: url('img/icons/404-<?= $login == "member" ? "black" : "white" ?>.png');"></div>
            <?php } else { ?>
              <div class="img" style="background-image: url('img/products/<?= $order["photo"]; ?>');"></div>
            <?php } ?>

            <div class="desc">
              <h3> <?= $order["name"] != null ? $order["name"] : "Unknown" ?> </h3>
              <p> IDR <?= $order["price"] ?> x <?= $order["amount"] ?></p>
            </div>

          </div>
          <?php $total_paid += $order["price"]; ?>
          <?php } ?>
        </section>

        <section class="wrapper">
          <table>

            <!-- total price -->
            <tr>
              <td> Total paid </td>
              <td> <center>:</center> </td>
              <td style="text-align: left;"><b> IDR <?= $mode == "buy" ? $total_paid : $order["total_paid"] ?> </b></td>
              <input type="number" name="total_paid" value="<?= $total_paid; ?>" hidden>
            
            </tr>

            <!-- datetime -->
            <tr>
              <td> <label for="datetime">Ordered on</label> </td>
              <td> <center>:</center> </td>
              <td> <input type="datetime" name="datetime" id="datetime" 
                    value="<?= $mode == "buy" ? date("Y-m-d H:i:s") : $order["datetime"] ?>" 
                    readonly class=" form-input <?= $login == "member" ? "white" : "black" ?>"></td>
            </tr>

            <!-- username-->
            <tr>
              <td> <label for="username"> Username </label> </td>
              <td> <center>:</center> </td>
              <td> <input type="text" name="username" id="username"
              value="<?= $orders[0]["username"] ?>"
              class="form-input <?= $login == "member" ? "white" : "black" ?>"
              readonly> </td>
            </tr>

            <!-- address -->
            <tr>
              <td> <label for="address"> Address </label> </td>
              <td> <center>:</center> </td>
              <td> <textarea name="address" id="address"
              class="form-input <?= $login == "member" ? "white" : "black" ?>"
              <?= $mode == "buy" ? "required" : "readonly" ?>><?= $orders[0]["address"] ?></textarea> </td>
            </tr>

            <!-- check out -->
            <tr>
              <td></td>
              <td></td>
              <td> 
                <?php if ($mode == "buy") { ?>
                  <button type="submit" name="buy" 
                  class="btn <?= $login == "member" ? "black" : "white" ?>">
                  Check Out</button> 
                <?php } ?>
            </td>
            </tr>

          </table>
          
        </section>
  
      </form>



    </div>

    <?php include "footer.php"; ?>

    <script src="js/style.js"></script>

  </div>

</body>

</html>