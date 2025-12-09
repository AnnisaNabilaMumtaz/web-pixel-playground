<?php
include 'db_connection.php';

if (isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type']; // "komunitas" atau "game"
    $id = intval($_GET['id']); // ID dari komunitas atau game

    // Tentukan tabel yang akan diperbarui
    $table = '';
    if ($type === 'komunitas') {
        $table = 'komunitas';
    } elseif ($type === 'game') {
        $table = 'game';
    }

    // Perbarui kolom clicks
    if ($table) {
        $sql = "UPDATE $table SET clicks = clicks + 1 WHERE id_{$type} = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            // Berhasil diperbarui
            http_response_code(200);
        } else {
            // Gagal memperbarui
            http_response_code(500);
            echo "Error updating clicks.";
        }
    } else {
        http_response_code(400);
        echo "Invalid type.";
    }
} else {
    http_response_code(400);
    echo "Invalid request.";
}
?>
