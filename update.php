<?php
// Konfigurasi database
$host = "localhost";
$username = "root";
$password = "";
$database = "twentyfour";

$koneksi = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Inisialisasi variabel
$error = "";
$success = "";

// Ambil data berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM orders WHERE id = $id";
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nama = $row['nama'];
        $no_hp = $row['no_hp'];
        $pilih_menu = $row['pilih_menu'];
        $jumlah_pesanan = $row['jumlah_pesanan'];
        $pesanan = $row['pesanan'];
        $pembayaran = $row['pembayaran'];
    } else {
        $error = "Data tidak ditemukan.";
    }
} else {
    header("Location: utama.php");
    exit;
}

// Proses UPDATE
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $pilih_menu = $_POST['pilih_menu'];
    $jumlah_pesanan = $_POST['jumlah_pesanan'];
    $pesanan = $_POST['pesanan'];
    $pembayaran = $_POST['pembayaran'];

    // Periksa apakah data valid dan proses update
    if (empty($nama) || empty($no_hp) || empty($pilih_menu) || empty($jumlah_pesanan) || empty($pesanan) || empty($pembayaran)) {
        $error = "Semua field harus diisi!";
    } else {
        $sql = "UPDATE orders SET nama='$nama', no_hp='$no_hp', pilih_menu='$pilih_menu', jumlah_pesanan='$jumlah_pesanan', pesanan='$pesanan', pembayaran='$pembayaran' WHERE id=$id";
        if ($koneksi->query($sql) === TRUE) {
            $success = "Data berhasil diperbarui.";
        } else {
            $error = "Gagal memperbarui data: " . $koneksi->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/update.css">
    <title>Update Member</title>
</head>

<body>
    <div class="container">
        <h2>Perbarui Data Member</h2>

        <!-- Notifikasi Pop-Up -->
        <?php if ($success): ?>
            <div class="popup success"><?php echo htmlspecialchars($success); ?></div>
        <?php elseif ($error): ?>
            <div class="popup error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $id; ?>"><br>

            <label for="nama">Nama:</label>
            <input type="text" name="nama" id="nama" value="<?php echo $nama; ?>" required><br>

            <label for="no_hp">No. HP:</label>
            <input type="text" name="no_hp" id="no_hp" value="<?php echo $no_hp; ?>" required><br>

            <label for="pilih_menu">Pilih Menu:</label>
            <select id="pilih_menu" name="pilih_menu" required>
                <option value="Classic Cheeseburger" <?php if ($pilih_menu == 'Classic Cheeseburger') echo 'selected'; ?>>Classic Cheeseburger</option>
                <option value="Double Patty Burger" <?php if ($pilih_menu == 'Double Patty Burger') echo 'selected'; ?>>Double Patty Burger</option>
                <option value="Spicy Chicken Burger" <?php if ($pilih_menu == 'Spicy Chicken Burger') echo 'selected'; ?>>Spicy Chicken Burger</option>
                <option value="Loaded Fries" <?php if ($pilih_menu == 'Loaded Fries') echo 'selected'; ?>>Loaded Fries</option>
                <option value="Crispy Chicken Strips" <?php if ($pilih_menu == 'Crispy Chicken Strips') echo 'selected'; ?>>Crispy Chicken Strips</option>
                <option value="Milkshake Trio" <?php if ($pilih_menu == 'Milkshake Trio') echo 'selected'; ?>>Milkshake Trio</option>
            </select><br>


            <label for="jumlah_pesanan">Jumlah Pesanan:</label>
            <input type="number" name="jumlah_pesanan" id="jumlah_pesanan" value="<?php echo $jumlah_pesanan; ?>" required><br>

            <label for="pesanan">Pesanan:</label>
            <select name="pesanan" id="pesanan" required>
                <option value="Takeaway" <?php if ($pesanan == 'Takeaway') echo 'selected'; ?>>Takeaway</option>
                <option value="Dine-in" <?php if ($pesanan == 'Dine-in') echo 'selected'; ?>>Dine-in</option>
            </select><br>


            <label for="pembayaran">Metode Pembayaran:</label>
            <select name="pembayaran" id="pembayaran" required>
                <option value="E-wallet" <?php if ($pembayaran == 'E-wallet') echo 'selected'; ?>>E-wallet</option>
                <option value="Transfer" <?php if ($pembayaran == 'Transfer') echo 'selected'; ?>>Transfer</option>
                <option value="Cash" <?php if ($pembayaran == 'Cash') echo 'selected'; ?>>Cash</option>
            </select><br>

            <div class="actions">
                <button type="submit" name="update" class="btn-save">Perbarui</button>
                <a href="utama.php" class="btn-back">Kembali</a>
            </div>
        </form>
    </div>
</body>
</html>
