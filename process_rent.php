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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["rent"])) {
    $user_id = $_SESSION["user_id"];
    $book_id = $_POST["book_id"];
    $purchase_date = date("Y-m-d");
    $rent_duration = $_POST["rent_duration"];
    $rent_price = 0;


    if ($rent_duration == "2 weeks") {
        $rent_price = "rent_price_2w";
    } elseif ($rent_duration == "1 month") {
        $rent_price = "rent_price_1m";
    } elseif ($rent_duration == "3 months") {
        $rent_price = "rent_price_3m";
    }


    $get_rent_price_sql = "SELECT $rent_price FROM books WHERE id = '$book_id'";
    $rent_price_result = $conn->query($get_rent_price_sql);

    if ($rent_price_result->num_rows > 0) {
        $row = $rent_price_result->fetch_assoc();
        $book_rent_price = $row[$rent_price];


        $insert_sql = "INSERT INTO purchases (user_id, book_id, purchase_date, total_price, rent_duration)
                       VALUES ('$user_id', '$book_id', '$purchase_date', '$book_rent_price', '$rent_duration')";

        if ($conn->query($insert_sql) === TRUE) {
            $insert_success = "Аренда успешно выполнена.";
        } else {
            $insert_error = "Ошибка при аренде: " . $conn->error;
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
    <title>Аренда книги</title>
</head>
<body>
    <h1>Аренда книги</h1>

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
