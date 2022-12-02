<?php

session_start();
require "config.php";

if (!isset($_SESSION["login"])) {
  echo "<script>
          alert('Please login first as Member or Admin!');
          document.location.href = 'login.php';
      </script>";
}

if (delete_product()) {
  echo "<script>
         document.location.href = 'products.php?mode=edit';
        </script>";
}

?>