<?php

session_start();
require "config.php";

if (!isset($_SESSION["login"])) {
  echo "<script>
          alert('Please login first as Admin!');
          document.location.href = 'login.php?mode=admin';
      </script>";
}

$mode = "add";

if (isset($_GET["mode"]) && $_GET["mode"] == "edit") {
  $mode = "edit";
  $id = $_GET["id"];
  $product = $db->query(
    "SELECT * FROM products
    WHERE product_id = '$id'"
  );
  $product = mysqli_fetch_array($product);
}

// tambah produk
if (isset($_POST["save"]) && $mode == "add") {
  if (add_product()) {
    echo "<script>
        document.location.href = 'products.php';
    </script>";

    // jika gagal, refresh
  } else {
    echo "<script>
          document.location.href = 'add-product.php?mode=$mode';
        </script>";
  }
}

// edit produk
if (isset($_POST["save"]) && $mode == "edit") {
  if (edit_product()) {
    echo "<script>
          document.location.href = 'products.php';
        </script>";
  } else {
    echo "<script>
              document.location.href = 'add-product.php?mode=$mode';
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
  <link rel="stylesheet" href="css/add-product.css?v=<?= time(); ?>">
  <link rel="shortcut icon" href="img/icons/logo.png" type="image/x-icon">

  <title> Add Product | Tarvita Cell </title>
</head>

<body>
  <div class="page-wrapper admin">

    <?php include "header.php"; ?>

    <div class="main-content">

      <section class="wrapper">
        <h1> <?= $mode == "add" ? "Add New Product" : $product["name"]; ?> </h1> <br>
        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
          <table>

            <!-- tanggal rilis -->
            <tr>
              <td><label for="date"> Date Released* </label></td>
              <td><center>:</center></td>
              <td><input type="date" name="date" id="date" class="form-input" value="<?= $mode == "edit" ? $product["date_released"] : date("Y-m-d"); ?>" readonly></td>
            </tr>

            <!-- nama  -->
            <tr>
              <td><label for="name"> Name* </label></td>
              <td><center>:</center></td>
              <td><input type="text" name="name" id="name" placeholder="Name" class="form-input" required autocomplete="off" value="<?= $mode == "edit" ? $product["name"] : ""; ?>"></td>
            </tr>

            <!-- harga -->
            <tr>
              <td><label for="price"> Price* </label></td>
              <td><center>:</center></td>
              <td> <input type="number" name="price" id="price" placeholder="Price (IDR)" class="form-input" required min="0" value="<?= $mode == "edit" ? $product["price"] : ""; ?>"></td>
            </tr>

            <!-- brand -->
            <tr>
              <td><label for="brand"> Brand* </label></td>
              <td><center>:</center></td>
              <td>
                <select name="brand" id="brand" class="form-input">
                  <option value="nokia" <?= $mode == "edit" && $product["brand"] == "nokia" ? "selected" : "" ?>> Nokia </option>
                  <option value="iphone" <?= $mode == "edit" && $product["brand"] == "iphone" ? "selected" : "" ?>> IPhone </option>
                  <option value="samsung" <?= $mode == "edit" && $product["brand"] == "samsung" ? "selected" : "" ?>> Samsung </option>
                  <option value="xiaomi" <?= $mode == "edit" && $product["brand"] == "xiaomi" ? "selected" : "" ?>> Xiaomi </option>
                  <option value="vivo" <?= $mode == "edit" && $product["brand"] == "vivo" ? "selected" : "" ?>> Vivo </option>
                </select>
              </td>
            </tr>

            <!-- stok -->
            <tr>
              <td><label for="stock"> Stock* </label></td>
              <td><center>:</center></td>
              <td> <input type="number" name="stock" id="stock" placeholder="Stock" class="form-input" required min="0" value="<?= $mode == "edit" ? $product["stock"] : ""; ?>"></td>
            </tr>

            <!-- color -->
            <tr>
              <td><label for="color"> Color* </label></td>
              <td><center>:</center></td>
              <td>
                <select name="color" id="color" class="form-input">
                  <option value="white" <?= $mode == "edit" && $product["color"] == "white" ? "selected" : "" ?>> White </option>
                  <option value="black" <?= $mode == "edit" && $product["color"] == "black" ? "selected" : "" ?>> Black </option>
                  <option value="gold" <?= $mode == "edit" && $product["color"] == "gold" ? "selected" : "" ?>> Gold </option>
                  <option value="silver" <?= $mode == "edit" && $product["color"] == "silver" ? "selected" : "" ?>> Silver </option>
                  <option value="blue" <?= $mode == "edit" && $product["color"] == "blue" ? "selected" : "" ?>> Blue </option>
                </select>
              </td>
            </tr>

            <!-- internal -->
            <tr>
              <td><label for="internal"> Internal* </label></td>
              <td><center>:</center></td>
              <td> <input type="number" name="internal" id="internal" placeholder="Internal (GB)" class="form-input" required min="0" value="<?= $mode == "edit" ? $product["internal"] : ""; ?>"></td>
            </tr>

            <!-- RAM -->
            <tr>
              <td><label for="ram"> RAM* </label></td>
              <td><center>:</center></td>
              <td> <input type="number" name="ram" id="ram" placeholder="RAM (GB)" class="form-input" required min="0" value="<?= $mode == "edit" ? $product["ram"] : ""; ?>"></td>
            </tr>

            <!-- battery -->
            <tr>
              <td><label for="battery"> Battery* </label></td>
              <td><center>:</center></td>
              <td> <input type="number" name="battery" id="battery" placeholder="Battery (mAh)" class="form-input" required min="0" value="<?= $mode == "edit" ? $product["battery"] : ""; ?>"></td>
            </tr>

            <!-- image -->
            <tr>
              <td><label for="image"> Image </label></td>
              <td><center>:</center></td>
              <td>

                <?php if ($mode == "edit") { ?>
                  <img src="img/products/<?= $product["photo"]; ?>" alt="" width="100"> <br>
                  <input type="text" name="old_image" value="<?= $product["photo"]; ?>" hidden>
                  <input type="text" name="id" value="<?= $product["product_id"]; ?>" hidden>
                <?php } ?>

                <input type="file" name="image" id="image" accept="image/*" class="form-input">
              </td>
            </tr>

            <!-- submit -->
            <tr>
              <td colspan="3">
                <center><br>
                  <button type="submit" name="save" class="btn white"> Save </button>
                </center>
              </td>
            </tr>

          </table>

        </form>
      </section>

    </div>

    <?php include "footer.php"; ?>

    <script src="js/style.js"></script>

  </div>

</body>

</html>