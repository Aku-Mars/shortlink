<?php
// koneksi ke database
$conn = new mysqli("localhost", "admin", "SOK1PSTIC", "shortlink_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

if (isset($_GET['c'])) {
    $shortCode = $_GET['c'];
    redirect($shortCode);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Shortlink</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        input[type="text"] {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        p {
            margin-top: 20px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Buat Shortlink</h1>
        <form method="POST" action="">
            <input type="text" name="url" placeholder="Masukkan URL" required>
            <button type="submit">Buat Shortlink</button>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $url = $_POST['url'];
            $shortCode = createShortLink($url);
            echo "<p>Shortlink Anda: <a href='https://akumars.dev/shortlink/" . $shortCode . "'>https://akumars.dev/shortlink/" . $shortCode . "</a></p>";
        }
        ?>
    </div>
</body>
</html>
