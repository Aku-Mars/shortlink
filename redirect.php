<?php
// koneksi ke database
$conn = new mysqli("localhost", "admin", "SOK1PSTIC", "shortlink_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// fungsi untuk mengarahkan shortlink ke URL asli
function redirect($shortCode) {
    global $conn;
    $stmt = $conn->prepare("SELECT original_url FROM shortlinks WHERE short_code = ?");
    $stmt->bind_param("s", $shortCode);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        header("Location: " . $row['original_url']);
        exit();
    } else {
        echo "Shortlink tidak ditemukan!";
    }
}

if (isset($_GET['code'])) {
    $shortCode = $_GET['code'];
    redirect($shortCode);
} else {
    echo "Shortlink tidak valid!";
}
?>
