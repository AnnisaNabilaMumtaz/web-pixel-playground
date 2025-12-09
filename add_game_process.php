<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_game = $_POST['nama_game'];
    $developer = $_POST['developer'];
    $batas_usia = $_POST['batas_usia'];
    $link_game = $_POST['link_game'];
    $deskripsi = $_POST['deskripsi'];
    $link_mabar = $_POST['link_mabar'];
    $link_komunitas = $_POST['link_komunitas'];

    // Upload file foto
    $foto_game = $_FILES['foto_game']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($foto_game);
    
    // Cek ekstensi file
    $allowed_extensions = ['jpg', 'jpeg', 'png'];
    $file_extension = pathinfo($foto_game, PATHINFO_EXTENSION);

    // Cek apakah ekstensi file termasuk yang diperbolehkan
    if (!in_array(strtolower($file_extension), $allowed_extensions)) {
        echo "Hanya file dengan format JPG, JPEG, atau PNG yang diperbolehkan.";
        exit;
    }

    // Cek MIME type file
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_mime = finfo_file($finfo, $_FILES['foto_game']['tmp_name']);
    finfo_close($finfo);

    $allowed_mimes = ['image/jpeg', 'image/png'];

    if (!in_array($file_mime, $allowed_mimes)) {
        echo "File yang di-upload bukan gambar yang valid.";
        exit;
    }

    // Pastikan file berhasil di-upload
    if (move_uploaded_file($_FILES['foto_game']['tmp_name'], $target_file)) {
        echo "File foto berhasil di-upload.";
    } else {
        echo "Terjadi kesalahan saat meng-upload foto.";
        exit;
    }

    // Simpan ke database
    $sql = "INSERT INTO game (nama_game, developer, batas_usia, link_game, deskripsi, foto_game, link_mabar, link_komunitas) 
            VALUES ('$nama_game', '$developer', '$batas_usia', '$link_game', '$deskripsi', '$target_file', '$link_mabar', '$link_komunitas')";

    if ($conn->query($sql) === TRUE) {
        echo "Game berhasil ditambahkan.";
        header("Location: admin_game_page.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
