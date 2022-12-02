    <?php if (!isset($_SESSION["login"])) { ?>
      <header>
        <a href="index.php" class="title"><h1>  Tarvita Cell  </h1> </a>

        <div class="menu-logo img" onclick="navActive()"></div>
        <ul class="menu">
          <a href="index.php" class="link-white"> <li> Home </li> </a>
          <a href="contact.php" class="link-white"> <li> Contact </li> </a>
          <a href="login.php" class="link-white"> <li> Login </li> </a>
        </ul>

      </header>
    <?php } else if ($_SESSION["login"] == "admin") { ?>
      <header>
        <span class="header-left"></span>
        <h1 class="title"> <a href="admin-home.php"> Tarvita Cell </a> </h1>
        <span class="header-right">
          <a href="logout.php" class="link-white"> Logout </a>
        </span>
      </header>

      <hr>

      <nav class="menu">
        <a href="admin-home.php" class="link-white"> Home </a>
        <a href="products.php" class="link-white"> Products </a>
        <a href="add-product.php" class="link-white"> Add Product </a>
        <a href="products.php?mode=edit" class="link-white"> Edit Product </a>
        <a href="orders.php" class="link-white"> Orders </a>
      </nav>

    <?php } else if ($_SESSION["login"] == "member") { ?>
        <header>
          <span class="header-left"></span>
          <h1 class="title"> <a href="member-home.php"> Tarvita Cell </a> </h1>
          <span class="header-right">
            <a href="logout.php" class="link-white"> Logout </a>
          </span>
        </header>

        <hr>

        <nav class="menu">
          <a href="member-home.php" class="link-white"> Home </a>
          <a href="profile.php" class="link-white"> Profile </a>
          <a href="products.php" class="link-white"> Products </a>
          <a href="cart.php" class="link-white"> Cart </a>
          <a href="orders.php" class="link-white"> Orders </a>
        </nav>
        
      <?php } ?> 