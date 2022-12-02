<?php

$db = new mysqli("localhost", "root", "", "tarvita_cell");

if (!$db) {
  die("Gagal terhubung! " . $db->connect_error);
}

date_default_timezone_set("Asia/Singapore");


function signing_up() {
  global $db;

  $username = $_POST["username"];
  $password = $_POST["password"];
  $konfirmasi = $_POST["konfirmasi"];
  $address = $_POST["address"];

  // cek apakah username sudah digunakan
  $result = $db->query(
    "SELECT username FROM users
     WHERE username='$username'"
  );

  // jika username sudah ada
  if (mysqli_num_rows($result) > 0) {
    echo "<script>
            alert('Username has been used!');
          </script>";
    return false;
  }

  // jika password != konfirmasi
  if ($password != $konfirmasi) {
    echo "<script>
            alert('Confirm password is wrong!');
          </script>";
    return false;
  }

  // enkripsi password
  $password = password_hash($password, PASSWORD_DEFAULT);

  // tambahkan akun baru ke database
  $result = $db->query(
    "INSERT INTO users
     VALUES ('$username', '$password', '$address')"
  );

  // jika gagal masuk ke database
  if (!$result) {
    echo "<script>
      alert('Registration failed!');
    </script>";
    return false;
  }

  // jika berhasil masuk ke database
  echo "<script>
          alert('Registration success!');
        </script>";
  return true;
}

function logging_in() {
  global $db;

  $username = $_POST["username"];
  $password = $_POST["password"];

  // cari akun di database
  $result = $db->query(
    "SELECT * FROM users
     WHERE username='$username'"
  );

  // jika akun tidak ditemukan
  if (mysqli_num_rows($result) == 0) {
    echo "<script>
            alert('Username not found!');
          </script>";
    return false;
  }

  $akun = mysqli_fetch_array($result);

  // jika password salah
  if (!password_verify($password, $akun["pw"])) {
    echo "<script>
            alert('Incorrect password!');
          </script>";
    return false;
  }

  echo "<script>
          alert('Logged in successfully!');
        </script>";
  return true;

}

function add_product() {
  global $db;

  $date = $_POST["date"];
  $name = $_POST["name"];
  $price = $_POST["price"];
  $brand = $_POST["brand"];
  $stock = $_POST["stock"];
  $color = $_POST["color"];
  $internal = $_POST["internal"];
  $ram = $_POST["ram"];
  $battery = $_POST["battery"];

  $id_baru = get_new_product_id($brand);
  $image = $id_baru.".png";

  // tidak upload gambar
  if ($_FILES["image"]["error"] === 4) {
    copy("img/icons/product.png", "img/products/$image");

  // upload gambar
  } else {
    if ($_FILES["image"]["size"] > 200000) {
      echo "<script>
              alert('Max size is 200 KB!');
          </script>";
      return false;
    }

    // ambil ekstensi
    $img_name = $_FILES['image']['name'];
    $pisah_titik = explode('.', $img_name);
    $ekstensi = strtolower(end($pisah_titik));

    // buat nama file
    $image = "$id_baru.$ekstensi"; // agar tidak ada nama file yang sama

    // pindahkan ke direktori img/products
    $tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp, "img/products/" . $image);

  }

  // simpan ke db
  $result = $db->query(
    "INSERT INTO products
    VALUES (
      '$id_baru',
      '$date',
      '$name',
      $price,
      '$brand',
      $stock,
      '$color',
      $internal,
      $ram,
      $battery,
      '$image'
    )"
  );

  // jika gagal disimpan ke db
  if (!$result) {
    echo "<script>
           alert('Failed to add product!');
          </script>";
    return false;
  }
  
  // jika berhasil simpan ke db
  echo "<script>
          alert('Successfully added product!');
          
        </script>";

  return true;
  
}

function edit_product() {
  global $db;

  $id = $_POST["id"];
  $date = $_POST["date"];
  $name = $_POST["name"];
  $price = $_POST["price"];
  $brand = $_POST["brand"];
  $stock = $_POST["stock"];
  $color = $_POST["color"];
  $internal = $_POST["internal"];
  $ram = $_POST["ram"];
  $battery = $_POST["battery"];

  $image = $_POST["old_image"];


  // upload gambar
  if ($_FILES["image"]["error"] !== 4) {

    if ($_FILES["image"]["size"] > 200000) {
      echo "<script>
              alert('Max size is 200 KB!');
          </script>";
      return false;
    }

    // hapus gambar lama
    unlink("img/products/$image");

    // ambil ekstensi
    $img_name = $_FILES['image']['name'];
    $pisah_titik = explode('.', $img_name);
    $ekstensi = strtolower(end($pisah_titik));

    // buat nama file
    $image = "$id.$ekstensi"; // agar tidak ada nama file yang sama

    // pindahkan ke direktori img/products
    $tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp, "img/products/" . $image);
  }

  // simpan ke db
  $result = $db->query(
    "UPDATE products
    SET name = '$name',
        price = $price,
        brand = '$brand',
        stock = $stock,
        color = '$color', 
        internal = $internal,
        ram = $ram,
        battery = $battery,
        photo = '$image'
    WHERE product_id='$id'"
  );

  // jika gagal disimpan ke db
  if (!$result) {
    echo "
    <script>
      alert('Failed to edit product!');
    </script>";
  }

  // jika berhasil simpan ke db
  echo "
      <script>
          alert('Product edited successfully!');
      </script>";


  return true;

}

function delete_product() {
  global $db;
  $id = $_GET["id"];

  $old_img = $db->query(
    "SELECT photo FROM products
    WHERE product_id='$id'"
  );

  // hapus gambar lama
  $old_img = mysqli_fetch_array($old_img);
  unlink("img/products/$old_img");

  $result = $db->query(
    "DELETE FROM products
    WHERE product_id='$id'"
  );

  if (!$result) {
    echo "<script>
           alert('Failed to delete product!');
          </script>";
    return false;
  }

  echo "<script>
         alert('Product deleted successfully!');
        </script>";
  return true;
}

function add_to_cart() {
  global $db;

  $username = $_SESSION["username"];
  $product_id = $_POST["product_id"];
  $amount = $_POST["amount"];

  $keranjang = $db->query(
    "SELECT * FROM cart_products
    WHERE username='$username' AND product_id='$product_id'"
  );

  // jika belum ada di keranjang
  if (mysqli_num_rows($keranjang) == 0) {
    $result = $db->query(
      "INSERT INTO cart_products
      VALUES ('$username', '$product_id', '$amount', 0)"
    );

  // jika sudah ada di keranjang
  } else {
    $result = $db->query(
      "UPDATE cart_products
      SET amount=$amount
      WHERE username='$username' AND product_id='$product_id'"
    );
  }

  if (!$result) {
    echo "<script>
          alert('Failed to add to cart!');
        </script>";
        return false;
  }
      
  return true;

}

function order_product() {
  global $db;

  $order_id = get_new_order_id();
  $username = $_POST["username"];
  $address = $_POST["address"];
  $datetime = $_POST["datetime"];
  $total_paid = $_POST["total_paid"];
  $status = "On Packaging";

  $result = $db->query(
    "INSERT INTO orders
    VALUES ('$order_id', '$datetime', $total_paid, '$status', '$address', '$username')"
  );

  if (!$result) {
    echo "<script>
          alert('Failed to place an order!');
        </script>";

    return false;
  }

  // tambahkan ke detail pesanan
  $products = $db->query(
    "SELECT * FROM cart_products
    WHERE username='$username' AND selected=1"
  );

  while ($product = mysqli_fetch_assoc($products)) {
    $id = $product["product_id"];
    $amount = $product["amount"];

    $db->query(
      "INSERT INTO order_products
      VALUES ('$order_id', '$id', $amount)"
    );

    // kurangi stok
    $result = $db->query(
      "UPDATE products
      SET stock= (
        SELECT stock FROM products
        WHERE product_id='$id'
      ) - $amount
      WHERE product_id='$id'"
    );
  }

  return true;
}

function update_status() {
  global $db;

  $order_id = $_POST["order_id"];
  $status = $_POST["status"];

  $result = $db->query(
    "UPDATE orders
    SET status='$status'
    WHERE order_id='$order_id'"
  );

  if (!$result) {
    echo "<script>
          alert('Failed to change status on $order_id!');
        </script>";

    return false;
  }

  return true;
}

/* ===================== TAMBAHAN =====================*/

function get_new_product_id($brand) {
  global $db;
  $ids = $db->query(
    "SELECT product_id FROM products
    WHERE product_id LIKE '%$brand%'
    ORDER BY product_id DESC"
  );

  if (mysqli_num_rows($ids) == 0) {
    $no_terakhir = 0; 
  } else {

    $id_terakhir = mysqli_fetch_assoc($ids);
    $id_terakhir = $id_terakhir["product_id"];
  
    $no_terakhir = explode("-", $id_terakhir);
    $no_terakhir = end($no_terakhir);
  }

  $id_baru = (int)$no_terakhir + 1;
  $id_baru = "$brand-$id_baru";

  return $id_baru;
}

function get_new_order_id() {
  global $db;

  $orders = $db->query(
    "SELECT order_id FROM orders"
  );

  $n_order = mysqli_num_rows($orders);
  $n_order = "$n_order";

  $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $sub = 10 - strlen($n_order) + 1;
  $random = substr(str_shuffle($chars), 0, $sub);

  $order_id = $random . $n_order;
  return $order_id;
}

?>