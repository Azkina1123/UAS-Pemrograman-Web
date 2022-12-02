<?php

session_start();

if (!isset($_SESSION["login"])) {
  echo "<script>
          alert('Please login first as Member!');
          document.location.href = 'login.php?mode=member';
      </script>";
}

$username = $_SESSION["username"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">
  <link rel="stylesheet" href="css/success.css?v=<?= time(); ?>">
  <link rel="shortcut icon" href="img/icons/logo.png" type="image/x-icon">

  <title> Home | Tarvita Cell </title>
</head>

<body>
  <div class="page-wrapper <?= $_SESSION["login"]; ?>">

    <?php include "header.php"; ?>

    <div class="main-content">

      <section class="wrapper">
        <center>
          <div class="img"></div>
          <p>
            <span>Successfully placed an order!</span> <br>
            Check it out on <a href="orders.php" class="link-black">orders page</a> !
          </p>
        </center>
        <br> <br> <br> 
      </section>


    </div>

    <?php include "footer.php"; ?>

    <script src="js/style.js"></script>

  </div>

</body>

</html>