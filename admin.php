<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || $_SESSION['username'] !== 'Admin') {
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


$sort_column = "title"; 
if (isset($_GET['sort'])) {
    $sort_column = $_GET['sort'];
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_book"])) {
    $delete_id = $_POST["delete_id"];
    $delete_sql = "DELETE FROM books WHERE id = '$delete_id'";
    if ($conn->query($delete_sql) === TRUE) {
        $delete_success = "Книга успешно удалена.";
    } else {
        $delete_error = "Ошибка при удалении книги: " . $conn->error;
    }
}

$sql = "SELECT * FROM books ORDER BY $sort_column";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель Администратора</title>
</head>
<body>
    <h1>Панель Администратора</h1>

    <button id="addBookButton">Добавить книгу</button>

    <div id="addBookForm" style="display: none;">
        <h2>Введите данные о книге</h2>
        <form method="post" action="process_add_book.php">
            <label>Название: <input type="text" name="title"></label><br>
            <label>Автор: <input type="text" name="author"></label><br>
            <label>Категория: <input type="text" name="category"></label><br>
            <label>Год написания: <input type="text" name="year"></label><br>
            <label>Цена покупки: <input type="text" name="price"></label><br>
            <label>Цена анренды на 2 недели: <input type="text" name="rent_price_2w"></label><br>
            <label>Цена анренды на 1 месяц: <input type="text" name="rent_price_1m"></label><br>
            <label>Цена анренды на 3 месяца: <input type="text" name="rent_price_3m"></label><br>
            <label>Статус:
                <select name="status">
                    <option value="available">Доступна</option>
                    <option value="not_available">Не доступна</option>
                </select>
            </label><br>
            </label><br>
            <input type="submit" name="submit" value="Добавить">
        </form>
    </div>

    <h2>Список книг</h2>

    <form method="get" action="admin.php">
        <label>Сортировать по:
            <select name="sort">
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
                  <a href='edit_book.php?id={$row['id']}'>Редактировать</a>
                  <form method='post' action='admin.php'>
                    <input type='hidden' name='delete_id' value='{$row['id']}'>
                    <input type='submit' name='delete_book' value='Удалить'>
                  </form>
                  </li>";
        }
        echo "</ul>";
    } else {
        echo "Нет доступных книг.";
    }
    ?>

    <a href="logout.php">Выйти</a>

    <script>
        document.getElementById("addBookButton").addEventListener("click", function () {
            var addBookForm = document.getElementById("addBookForm");
            addBookForm.style.display = "block";
        });
    </script>
</body>
</html>
