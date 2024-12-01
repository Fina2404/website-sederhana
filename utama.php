<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/utama.css">
    <title>CRUD System</title>
</head>
<body>

<header class="headere">
    <a href="#home" class="logo">Twenty <span>Four</span></a>
    <ul class="navbar">
        <li><a href="admin.php">Logout</a></li>
    </ul>
</header>

<div class="container">
    <h2>Daftar Pembeli</h2>

    <!-- Form Pencarian -->
    <form method="GET" action="" class="search-form">
        <input type="text" name="search" placeholder="Cari berdasarkan nama" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit" class="cari">Cari</button>
        <button type="button" class="reset" onclick="window.location.href='?'">Reset</button>
    </form>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Nomor HP</th>
                    <th>Menu</th>
                    <th>Jumlah Pesanan</th>
                    <th>Pesanan</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Koneksi ke database
                $conn = new mysqli("localhost", "root", "", "twentyfour");
                if ($conn->connect_error) {
                    die("Koneksi gagal: " . $conn->connect_error);
                }

                // Hapus data jika dikonfirmasi
                if (isset($_GET['delete_id'])) {
                    $id = $_GET['delete_id'];
                    $sql1 = "DELETE FROM orders WHERE id = '$id'";
                    $q1 = $conn->query($sql1);
                    if ($q1) {
                        echo "<script>
                                setTimeout(() => {
                                    document.getElementById('popup-success').style.display = 'block';
                                    setTimeout(() => window.location.href = 'utama.php', 2000);
                                }, 500);
                              </script>";
                    }
                }

                // Pengaturan Pagination
                $limit = 5; // Jumlah data per halaman
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                // Mencari data berdasarkan input pencarian
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $sql = "SELECT * FROM orders WHERE nama LIKE '%$search%' LIMIT $limit OFFSET $offset";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . $row["id"] . "</td>
                            <td>" . $row["nama"] . "</td>
                            <td>" . $row["no_hp"] . "</td>
                            <td>" . $row['pilih_menu'] . "</td>
                            <td>" . $row["jumlah_pesanan"] . "</td>
                            <td>" . $row["pesanan"] . "</td>
                            <td>" . $row["pembayaran"] . "</td>
                            <td>
                                <a href='update.php?id=" . $row["id"] . "' class='btn-edit'>Edit</a>
                                <button class='btn-delete' onclick=\"showDeleteModal('" . $row["id"] . "')\">Hapus</button>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>Tidak ada data</td></tr>";
                }

                // Menghitung total halaman
                $sql_total = "SELECT COUNT(*) as total FROM orders WHERE nama LIKE '%$search%'";
                $result_total = $conn->query($sql_total);
                $total_data = $result_total->fetch_assoc()['total'];
                $total_pages = ceil($total_data / $limit);

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>" <?php if ($i == $page) echo 'class="active"'; ?>>
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>">Next</a>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div id="modal" class="modal">
    <div class="modal-content">
        <p>Apakah Anda yakin ingin menghapus data ini?</p>
        <div class="modal-buttons">
            <button id="confirm-delete" class="btn-confirm">Ya</button>
            <button onclick="hideModal()" class="btn-cancel">Tidak</button>
        </div>
    </div>
</div>

<!-- Pop-Up Sukses -->
<div id="popup-success" class="popup-success">
    <p>✅ Data berhasil dihapus!</p>
</div>

<script>
    // Menampilkan modal konfirmasi hapus
    function showDeleteModal(id) {
        const modal = document.getElementById('modal');
        const confirmDelete = document.getElementById('confirm-delete');

        // Menyimpan ID untuk penghapusan
        confirmDelete.onclick = () => {
            window.location.href = '?delete_id=' + id; // Mengarahkan ke halaman dengan parameter delete_id
        };

        modal.style.display = 'flex';
    }

    // Menyembunyikan modal konfirmasi
    function hideModal() {
        const modal = document.getElementById('modal');
        modal.style.display = 'none';
    }

    // Fungsi menampilkan pop-up notifikasi sukses
    function showSuccessPopup() {
        const popup = document.getElementById('popup-success');
        popup.style.display = 'block';
        setTimeout(() => {
            popup.style.display = 'none';
        }, 3000);
    }

    // Pemanggilan otomatis notifikasi sukses jika berhasil menghapus
    if (window.location.search.includes('success=true')) {
        showSuccessPopup();
    }
</script>

</body>
</html>
