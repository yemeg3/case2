<?php
session_start();

$servername = "localhost";
$username = "famas211_root";
$password = "vostcorp12Qaq";
$dbname = "famas211_root";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $category = $_POST["category"];
    $year = $_POST["year"];
    $price = $_POST["price"];
    $rent_price_2w = $_POST["rent_price_2w"];
    $rent_price_1m = $_POST["rent_price_1m"];
    $rent_price_3m = $_POST["rent_price_3m"];
    $status = $_POST["status"];

    $insert_sql = "INSERT INTO books (title, author, category, year, price, rent_price_2w, rent_price_1m, rent_price_3m, status)
                   VALUES ('$title', '$author', '$category', '$year', '$price', '$rent_price_2w', '$rent_price_1m', '$rent_price_3m', '$status')";

    if ($conn->query($insert_sql) === TRUE) {
        $insert_success = "Книга успешно добавлена.";
    } else {
        $insert_error = "Ошибка при добавлении книги: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление книги</title>
</head>
<body>
    <h1>Добавление книги</h1>

    <?php
    if (isset($insert_success)) {
        echo "<p style='color: green;'>$insert_success</p>";
    } elseif (isset($insert_error)) {
        echo "<p style='color: red;'>$insert_error</p>";
    }
    ?>

    <a href="admin.php">Вернуться</a>
</body>
</html>
