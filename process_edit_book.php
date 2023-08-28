<?php
include("session_start.php");

$servername = "localhost";
$username = "famas211_root";
$password = "vostcorp12Qaq";
$dbname = "famas211_root";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $book_id = $_POST["book_id"];
    $new_title = $_POST["title"];
    $new_author = $_POST["author"];
    $new_category = $_POST["category"];
    $new_year = $_POST["year"];
    $new_price = $_POST["price"];
    $new_rent_price_2w = $_POST["rent_price_2w"];
    $new_rent_price_1m = $_POST["rent_price_1m"];
    $new_rent_price_3m = $_POST["rent_price_3m"];
    $status = $_POST["status"];


    $sql = "UPDATE books SET title = '$new_title', author = '$new_author', category = '$new_category', year = '$new_year', price = '$new_price', rent_price_2w = '$new_rent_price_2w', rent_price_1m = '$new_rent_price_1m', rent_price_3m = '$new_rent_price_3m', status = '$status' WHERE id = '$book_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Ошибка при обновлении книги: " . $conn->error;
    }
}

$conn->close();
?>
