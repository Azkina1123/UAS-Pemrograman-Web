<?php

require "config.php";

if (isset($_POST["signup"])) {
  if (signing_up()) {
    echo "<script>
            document.location.href = 'login.php';
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
  <link rel="stylesheet" href="css/login.css?v=<?= time(); ?>">
  <link rel="shortcut icon" href="img/icons/logo.png" type="image/x-icon">

  <title> Sign Up | Tarvita Cell </title>
</head>

<body>
  <div class="page-wrapper user">

    <?php include "header.php"; ?>

    <div class="main-content">

      <section class="wrapper">
        <div class="box">
          <h1> Register as Member </h1> <br>

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

              <!-- konfirmasi -->
              <tr>
                <td><label for="konfirmasi"> Confirm <br> Password* </label></td>
                <td><center>:</center></td>
                <td><input type="password" name="konfirmasi" id="konfirmasi" 
                placeholder="Confirm Password" class="form-input" autocomplete="off" required></td>
              </tr>

              <!-- address -->
              <tr>
                <td><label for="address"> Address* </label></td>
                <td><center>:</center></td>
                <td>
                  <textarea name="address" id="address" placeholder="Address" class="form-input" 
                  autocomplete="off" required maxlength="100"></textarea>
                </td>
              </tr>

              <!-- submit -->
              <tr>
                <td colspan="3"><center>
                    <button type="submit" name="signup" class="btn black"> Sign Up </button>
                </center></td>
              </tr>

              <!-- sign up -->
              <tr>
                <td colspan="3"><center>
                    <a href="login.php" class="link-black">Already have an account? <br> Login now!</a>
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