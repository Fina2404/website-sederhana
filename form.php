<?php
// Konfigurasi database
$host = "localhost";
$username = "root";
$password = "";
$database = "twentyfour";

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Variabel untuk pesan
$successMessage = "";
$errorMessage = "";

// Ambil data dari form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $pilih_menu = $_POST['pilih_menu'];
    $jumlah_pesanan = $_POST['jumlah_pesanan'];
    $pesanan = $_POST['pesanan'];
    $pembayaran = $_POST['pembayaran'];

    // Simpan data ke database
    $sql = "INSERT INTO orders (nama, no_hp, pilih_menu, jumlah_pesanan, pesanan, pembayaran)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiss", $nama, $no_hp, $pilih_menu, $jumlah_pesanan, $pesanan, $pembayaran);

    if ($stmt->execute()) {
        $successMessage = "Order berhasil!";
    } else {
        $errorMessage = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Tutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/form.css">
  <title>Form Order</title>
</head>
<body>

  <header class="header">
    <h1>Selamat datang di TwentyFour</h1>
  </header>

  <!-- Notifikasi popup -->
  <?php if (!empty($successMessage)): ?>
    <div class="popup success">
        <p><?= htmlspecialchars($successMessage); ?></p>
    </div>
  <?php elseif (!empty($errorMessage)): ?>
    <div class="popup error">
        <p><?= htmlspecialchars($errorMessage); ?></p>
    </div>
  <?php endif; ?>

  <h2>Silakan Order</h2>

  <form action="" method="post">
    <label for="nama">Nama:</label>
    <input type="text" id="nama" name="nama" required><br><br>

    <label for="no_hp">Nomor HP:</label>
    <input type="text" id="no_hp" name="no_hp" required><br><br>

    <label for="pilih_menu">Pilih Menu:</label>
    <select id="pilih_menu" name="pilih_menu" required>
      <option value="Classic Cheeseburger">Classic Cheeseburger</option>
      <option value="Double Patty Burger">Double Patty Burger</option>
      <option value="Spicy Chicken Burger">Spicy Chicken Burger</option>
      <option value="Loaded Fries">Loaded Fries</option>
      <option value="Crispy Chicken Strips">Crispy Chicken Strips</option>
      <option value="Milkshake Trio">Milkshake Trio</option>
    </select><br><br>

    <label for="jumlah_pesanan">Jumlah Pesanan:</label>
    <input type="number" id="jumlah_pesanan" name="jumlah_pesanan" min="1" required><br><br>

    <label for="pesanan">Pesanan:</label>
    <select id="pesanan" name="pesanan" required>
      <option value="takeaway">Takeaway</option>
      <option value="dine-in">Dine-in</option>
    </select><br><br>

    <label for="pembayaran">Pembayaran:</label>
    <select id="pembayaran" name="pembayaran" required>
      <option value="cash">Cash</option>
      <option value="e-wallet">E-Wallet</option>
      <option value="card">Card</option>
    </select><br><br>

    <button type="submit">Order</button>
  </form>

  <!-- Tambahkan script untuk close popup -->
  <script>
    const popup = document.querySelector('.popup');
    if (popup) {
      setTimeout(() => {
        popup.style.opacity = '0';
        popup.style.display = 'none';
      }, 3000); // Popup menghilang setelah 3 detik
    }
  </script>

</body>
</html>
