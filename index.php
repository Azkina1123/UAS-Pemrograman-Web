<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">
  <link rel="stylesheet" href="css/index.css?v=<?= time(); ?>">
  <link rel="shortcut icon" href="img/icons/logo.png" type="image/x-icon">

  <title> Home | Tarvita Cell </title>
</head>

<body>
  <div class="page-wrapper">

    <?php include "header.php"; ?>

    <div class="main-content">

      <section class="wrapper intro img">

        <div class="desc">

          <h1> Tarvita Cell </h1>
          <p> Raise your level with quality smartphones from various famous brands. </p>

        </div>

      </section>

      <section class="wrapper preview">

        <div class="brands container">

          <div class="xiaomi brand">
            <div class="img"></div>
            <h1> Xiaomi </h1>
          </div>

          <div class="samsung brand">
            <div class="img"></div>
            <h1> Samsung </h1>
          </div>

          <div class="iphone brand">
            <div class="img"></div>
            <h1> IPhone</h1>
          </div>

        </div>

        <div class="note">
          <p> And there's more other famous brands. </p>
        </div>

      </section>

      <section class="wrapper advert">
        <div class="img"></div>
        <div class="desc">
          <p>
            We present you smartphones that suit your taste with great specifications.
          </p>

          <a href="login.php">
            <button class="btn black"> Purchase Now! </button>
          </a>
        </div>

      </section>

    </div>

    <?php include "footer.php"; ?>

    <script src="js/style.js"></script>

  </div>

</body>

</html>