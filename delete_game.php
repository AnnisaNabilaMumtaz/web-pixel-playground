<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id_game = $_GET['id'];

    // Hapus file foto jika ada
    $result = $conn->query("SELECT foto_game FROM game WHERE id_game = $id_game");
    $row = $result->fetch_assoc();
    if ($row['foto_game'] && file_exists($row['foto_game'])) {
        unlink($row['foto_game']);
    }

    // Hapus dari database
    $sql = "DELETE FROM game WHERE id_game = $id_game";
    if ($conn->query($sql) === TRUE) {
        echo "Game berhasil dihapus.";
        header("Location: admin_game_page.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
