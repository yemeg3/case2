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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["purchase"])) {
    $user_id = $_SESSION["user_id"];
    $book_id = $_POST["book_id"];
    $purchase_date = date("Y-m-d");
    $rent_duration = "bought";

    $get_price_sql = "SELECT price FROM books WHERE id = '$book_id'";
    $price_result = $conn->query($get_price_sql);

    if ($price_result->num_rows > 0) {
        $row = $price_result->fetch_assoc();
        $book_price = $row["price"];

    
        $insert_sql = "INSERT INTO purchases (user_id, book_id, purchase_date, total_price, rent_duration)
                       VALUES ('$user_id', '$book_id', '$purchase_date', '$book_price', '$rent_duration')";

        if ($conn->query($insert_sql) === TRUE) {
            $insert_success = "Покупка успешно выполнена.";
        } else {
            $insert_error = "Ошибка при покупке: " . $conn->error;
        }
    } else {
        $insert_error = "Книга не найдена.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Покупка книги</title>
</head>
<body>
    <h1>Покупка книги</h1>

    <?php
    if (isset($insert_success)) {
        echo "<p style='color: green;'>$insert_success</p>";
    } elseif (isset($insert_error)) {
        echo "<p style='color: red;'>$insert_error</p>";
    }
    ?>

    <a href="user.php">Вернуться</a>
</body>
</html>
