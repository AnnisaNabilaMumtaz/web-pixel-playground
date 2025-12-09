<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id_komunitas = $_GET['id'];

    // Hapus file foto jika ada
    $result = $conn->query("SELECT foto_komunitas FROM komunitas WHERE id_komunitas = $id_komunitas");
    $row = $result->fetch_assoc();
    if ($row['foto_komunitas'] && file_exists($row['foto_komunitas'])) {
        unlink($row['foto_komunitas']);
    }

    // Hapus dari database
    $sql = "DELETE FROM komunitas WHERE id_komunitas = $id_komunitas";
    if ($conn->query($sql) === TRUE) {
        echo "Komunitas berhasil dihapus.";
        header("Location: admin_komunitas_page.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
