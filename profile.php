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
$akun = $db->query(
  "SELECT address FROM users
  WHERE username='$username'"
);
$akun = mysqli_fetch_assoc($akun);

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">
  <link rel="stylesheet" href="css/profile.css?v=<?= time(); ?>">
  <link rel="shortcut icon" href="img/icons/logo.png" type="image/x-icon">

  <title> Profile | Tarvita Cell </title>

</head>

<body>
  <div class="page-wrapper <?= $_SESSION["login"]; ?>">

    <?php include "header.php"; ?>

    <div class="main-content">

      <section class="wrapper">
        <center>
        <h1> Your Profile </h1> <br>
        <table>
          <tr>
            <td> Username </td>
            <td> <center>:</center> </td>
            <td> <?= $username; ?> </td>
          </tr>
          <tr>
            <td> Address </td>
            <td> <center>:</center> </td>
            <td> <?= $akun["address"]; ?> </td>
          </tr>
        </table>
        </center>
        <br> <br> <br> <br> <br>
      </section>


    </div>

    <?php include "footer.php"; ?>

    <script src="js/style.js"></script>

  </div>

</body>

</html>