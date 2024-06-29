<?php
// koneksi ke database
$conn = new mysqli("localhost", "username", "password", "database");

// fungsi untuk membuat shortlink
function createShortLink($url) {
    global $conn;
    $shortCode = substr(md5(uniqid(rand(), true)), 0, 6); // membuat kode pendek
    $stmt = $conn->prepare("INSERT INTO shortlinks (short_code, original_url) VALUES (?, ?)");
    $stmt->bind_param("ss", $shortCode, $url);
    $stmt->execute();
    return $shortCode;
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

// contoh penggunaan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'];
    $shortCode = createShortLink($url);
    echo "Shortlink Anda: " . "https://domain-anda.com/" . $shortCode;
} elseif (isset($_GET['c'])) {
    $shortCode = $_GET['c'];
    redirect($shortCode);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buat Shortlink</title>
</head>
<body>
    <form method="POST" action="index.php">
        <input type="text" name="url" placeholder="Masukkan URL" required>
        <button type="submit">Buat Shortlink</button>
    </form>
</body>
</html>
