<?php

session_start();
require "config.php";

if (!isset($_SESSION["login"])) {
   echo "<script>
          alert('Please login first as Member or Admin!');
          document.location.href = 'login.php';
          </script>";
        }
        
        session_unset();
        session_destroy();
        
echo "<script>
        alert('See you again!');
        document.location.href = 'index.php';
      </script>";
?>
