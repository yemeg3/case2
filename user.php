<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['username'] === 'Admin') {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "famas211_root";
$password = "vostcorp12Qaq";
$dbname = "famas211_root";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'title';

$sql = "SELECT * FROM books ORDER BY $sort_by";
$result = $conn->query($sql);



$current_date = date("Y-m-d");
$reminder_sql = "SELECT b.title, p.purchase_date, p.rent_duration
                 FROM purchases p
                 INNER JOIN books b ON p.book_id = b.id
                 WHERE p.user_id = '{$_SESSION['user_id']}'
                 ORDER BY $sort_by";

$reminder_result = $conn->query($reminder_sql);



$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин книг "Data Book</title>
</head>
<body>
    <h1>Магазин книг "Data Book"</h1>

    <h2>Список книг</h2>
    <form method="get">
        <label>Сортировать по:
            <select name="sort_by">
                <option value="category">Категории</option>
                <option value="author">Автору</option>
                <option value="year">Году написания</option>
            </select>
            <input type="submit" value="Применить">
        </label>
    </form>
    <?php
    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['title']} ({$row['author']})
                  <a href='view_book.php?id={$row['id']}'>Просмотреть</a>
                  </li>";
        }
        echo "</ul>";

    } else {
        echo "Нет доступных книг.";
    }
    ?>

    <?php
    if ($reminder_result->num_rows > 0) {
        echo "<h2>Напоминания о близком окончании аренды (менее 10)</h2>";

        while ($reminder_row = $reminder_result->fetch_assoc()) {
            $rent_end_date = date("Y-m-d", strtotime($reminder_row['purchase_date'] . " + {$reminder_row['rent_duration']}"));
            $days_left = date_diff(date_create($current_date), date_create($rent_end_date))->days;
            if ($days_left <= 10) {
                echo "Напоминаем, что до окончания аренды книги '{$reminder_row['title']}' осталось $days_left дней.";
            }
        }
        echo "<ul>";
        echo "</ul>";
    }
    ?>

    <a href="logout.php">Выйти</a>
</body>
</html>
