<?php
session_start();

// Array user dengan username dan password
$users = [
    ['username' => 'f', 'password' => '1'],
];

// Variabel untuk pesan error
$errorMessage = "";

// Proses login
if (isset($_POST['Login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userFound = false; // Menyimpan status apakah username ditemukan
    $passwordCorrect = false; // Menyimpan status apakah password cocok

    // Validasi input kosong
    if (empty($username) || empty($password)) {
        $errorMessage = "Username dan Password masih kosong!";
    } else {
        // Periksa username dan password dalam array
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                $userFound = true;
                if ($user['password'] === $password) {
                    $passwordCorrect = true;
                    // Login berhasil
                    $_SESSION['login'] = 1;
                    $_SESSION['username'] = $username;
                    header("Location: utama.php");
                    exit();
                }
                break; // Hentikan loop jika username ditemukan
            }
        }

        // Berikan pesan error sesuai kondisi
        if (!$userFound) {
            $errorMessage = "Username tidak terdaftar!";
        } elseif (!$passwordCorrect) {
            $errorMessage = "Password yang dimasukkan salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Login</title>
  <link rel="stylesheet" href="css/admin.css">
</head>

<body>
  <!-- Header -->
  <header class="header">
    <h1>Selamat Datang</h1>
    <p>Website Sederhana</p>
  </header>

  <!-- Notifikasi popup -->
  <?php if (!empty($errorMessage)): ?>
    <div class="popup">
      <p><?= htmlspecialchars($errorMessage); ?></p>
      <button class="close-btn" onclick="closePopup()">×</button>
    </div>
  <?php endif; ?>

  <!-- Form Login -->
  <div class="container">
    <div class="form">
      <h2>Admin</h2>
      <div class="user">
        <form method="POST" action="">
            <input type="text" name="username" placeholder="User ID" required>
            <input type="password" name="password" placeholder="Password" required>
      </div>
        <input type="submit" name="Login" value="Login">
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <p>&copy; 2024 Modify By finadwiaulia.</p>
  </footer>

  <!-- Tambahkan script untuk close popup -->
  <script>
    const popup = document.querySelector('.popup');
    if (popup) {
      setTimeout(() => {
        popup.style.opacity = '0';
        popup.style.display = 'none';
      }, 3000); // Popup menghilang setelah 3 detik
    }

    // Fungsi untuk menutup popup
    function closePopup() {
      if (popup) popup.style.display = 'none';
    }
  </script>
</body>
</html>