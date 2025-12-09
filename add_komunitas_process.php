<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_komunitas = $conn->real_escape_string($_POST['nama_komunitas']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $link_komunitas = $conn->real_escape_string($_POST['link_komunitas']);
    $id_game = (int) $_POST['id_game']; // Pastikan id_game adalah integer

    // Upload file foto
    $foto_komunitas = $_FILES['foto_komunitas']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($foto_komunitas);

    // Cek ekstensi file
    $allowed_extensions = ['jpg', 'jpeg', 'png'];
    $file_extension = strtolower(pathinfo($foto_komunitas, PATHINFO_EXTENSION));

    if (!in_array($file_extension, $allowed_extensions)) {
        echo "Hanya file dengan format JPG, JPEG, atau PNG yang diperbolehkan.";
        exit;
    }

    // Cek MIME type file
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_mime = finfo_file($finfo, $_FILES['foto_komunitas']['tmp_name']);
    finfo_close($finfo);

    $allowed_mimes = ['image/jpeg', 'image/png'];

    if (!in_array($file_mime, $allowed_mimes)) {
        echo "File yang di-upload bukan gambar yang valid.";
        exit;
    }

    // Pastikan file berhasil di-upload
    if (!move_uploaded_file($_FILES['foto_komunitas']['tmp_name'], $target_file)) {
        echo "Terjadi kesalahan saat meng-upload foto.";
        exit;
    }

    // Simpan ke database
    $sql = "INSERT INTO komunitas (nama_komunitas, deskripsi, link_komunitas, id_game, foto_komunitas) 
            VALUES ('$nama_komunitas', '$deskripsi', '$link_komunitas', '$id_game', '$target_file')";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_komunitas_page.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
