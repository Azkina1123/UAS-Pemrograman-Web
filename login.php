<?php

session_start();

require "config.php";

$mode = "member";

if (isset($_SESSION["login"])) {
  $_SESSION["login"] == "admin" ?
  header("Location: admin-home.php") :
  header("Location: member-home.php");
}

if (isset($_GET["mode"]) && $_GET["mode"] == "admin") {
  $mode = "admin";
}

if (isset($_POST["login"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // login admin
  if (
    $mode == "admin" && 
    $username == "admin" && 
    $password == "123"
  ) {

    $_SESSION["login"] = "admin";

    echo "<script>
          alert('Proses login sebagai admin berhasil!');
          document.location.href = 'admin-home.php';
        </script>";

  // login member
  } else if ($mode == "member") {
    if (logging_in()) {
      $_SESSION["username"] = $username;
      $_SESSION["login"] = "member";

      echo "<script>
            document.location.href = 'member-home.php';
          </script>";
    }
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
  <link rel="stylesheet" href="css/login.css?v=<?= time(); ?>">
  <link rel="shortcut icon" href="img/icons/logo.png" type="image/x-icon">

  <title> Login | Tarvita Cell </title>
</head>

<body>
  <div class="page-wrapper">

    <?php include "header.php"; ?>

    <div class="main-content">

      <section class="wrapper">
        <div class="box">
          <h1> Login as <?= ucfirst($mode); ?> </h1>
          <a href="?mode=<?= $mode == "member"? "admin" : "member"; ?>" 
          class="link-black"> 
            <p> Login as <?= $mode == "member"? ucfirst("admin") : ucfirst("member"); ?></p> 
          </a>

          <form action="" method="POST"> 
          <table>

            <!-- username -->
            <tr>
              <td><label for="username"> Username* </label></td>
              <td><center>:</center></td>
              <td><input type="text" name="username" id="username"  
              placeholder="Username" class="form-input" autocomplete="off" 
              required maxlength="10"></td>
            </tr>

            <!-- password -->
            <tr>
              <td><label for="password"> Password* </label></td>
              <td><center>:</center></td>
              <td><input type="password" name="password" id="password"  
              placeholder="Password" class="form-input" autocomplete="off" required></td>
            </tr>

            <!-- submit -->
            <tr>
              <td colspan="3"><center>
                <button type="submit" name="login" class="btn black"> Login </button>
              </center></td>
            </tr>

            <!-- sign up -->
            <tr>
              <td colspan="3"><center>
                <a href="signup.php" class="link-black">Not a member yet? <br> Sign up now!</a>
              </center></td>
            </tr>
          </table>

          </form>
        </div>
      </section>

    </div>

    <?php include "footer.php"; ?>

    <script src="js/style.js"></script>

  </div>

</body>

</html>