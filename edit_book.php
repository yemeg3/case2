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

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $book_id = $_GET["id"];
    $sql = "SELECT * FROM books WHERE id = '$book_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $book_data = $result->fetch_assoc();
    } else {
        $book_not_found = true;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование книги</title>
</head>
<body>
    <h1>Редактирование книги</h1>

    <?php
    if (isset($book_data)) {
    
        echo "<form method='post' action='process_edit_book.php'>";
        echo "<input type='hidden' name='book_id' value='{$book_data['id']}'>";
        echo "<label>Название: <input type='text' name='title' value='{$book_data['title']}'></label><br>";
        echo "<label>Автор: <input type='text' name='author' value='{$book_data['author']}'></label><br>";
        echo "<label>Категория: <input type='text' name='category' value='{$book_data['category']}'></label><br>";
        echo "<label>Год написания: <input type='text' name='year' value='{$book_data['year']}'></label><br>";
        echo "<label>Цена покупки: <input type='text' name='price' value='{$book_data['price']}'></label><br>";
        echo "<label>Цена аренды на 2 недели: <input type='text' name='rent_price_2w' value='{$book_data['rent_price_2w']}'></label><br>";
        echo "<label>Цена аренды на 1 месяц: <input type='text' name='rent_price_1m' value='{$book_data['rent_price_1m']}'></label><br>";
        echo "<label>Цена аденды на 2 месяца: <input type='text' name='rent_price_3m' value='{$book_data['rent_price_3m']}'></label><br>";
        echo "<label>Статус:
              <select name='status'>
              <option value='available'>Доступна</option>
              <option value='not_available'>Не доступна</option>
              </select>
              </label><br>";

        echo "<input type='submit' name='submit' value='Сохранить'>";
        echo "</form>";
    } elseif (isset($book_not_found)) {
        echo "<p>Книга не найдена.</p>";
    }
    ?>

    <a href="admin.php">Назад</a>
</body>
</html>
