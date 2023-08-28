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


        $user_id = $_SESSION["user_id"];
        $check_purchase_sql = "SELECT * FROM purchases WHERE user_id = '$user_id' AND book_id = '$book_id'";
        $check_purchase_result = $conn->query($check_purchase_sql);

        if ($check_purchase_result->num_rows > 0) {
            $book_already_purchased = true;
            $purchase_data = $check_purchase_result->fetch_assoc();
        }
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
    <title>Просмотр книги</title>
</head>
<body>
    <h1>Просмотр книги</h1>

    <?php
    if (isset($book_data)) {
        echo "<p>Название: {$book_data['title']}</p>";
        echo "<p>Автор: {$book_data['author']}</p>";
        echo "<p>Категория: {$book_data['category']}</p>";
        echo "<p>Год написания: {$book_data['year']}</p>";
        echo "<p>Цена покупки: {$book_data['price']}</p>";
        echo "<p>Цена анренды на 2 недели: {$book_data['rent_price_2w']}</p>";
        echo "<p>Цена анренды на 1 месяц: {$book_data['rent_price_1m']}</p>";
        echo "<p>Цена анренды на 3 месяца: {$book_data['rent_price_3m']}</p>";

        if ($book_data['status'] === 'not_available') {
            echo "<p>Статус: Не доступна в данный момент</p>";
        } else {
          if (isset($book_already_purchased)) {
              if ($purchase_data['rent_duration'] == '2 weeks' || $purchase_data['rent_duration'] == '1 month' || $purchase_data['rent_duration'] == '3 months') {
                  $rent_end_date = date("d.m.Y", strtotime($purchase_data['purchase_date'] . " + {$purchase_data['rent_duration']}"));
                  echo "<p>В аренде до: $rent_end_date</p>";
              } elseif ($purchase_data['rent_duration'] == 'bought') {
                  echo "<p>Куплено</p>";
              }
          } else {

              if ($_SESSION['username'] !== 'Admin') {
                  echo "<form method='post' action='process_purchase.php'>";
                  echo "<input type='hidden' name='book_id' value='{$book_data['id']}'>";
                  if ($check_purchase_result->num_rows == 0) {
                      echo "<input type='submit' name='purchase' value='Купить'>";
                  } else {
                      echo "<button type='button' disabled>Куплено</button>";
                  }
                  echo "</form>";

                  echo "<form method='post' action='process_rent.php'>";
                  echo "<input type='hidden' name='book_id' value='{$book_data['id']}'>";
                  echo "<select name='rent_duration'>
                          <option value='2 weeks'>2 недели</option>
                          <option value='1 month'>1 месяц</option>
                          <option value='3 months'>3 месяца</option>
                        </select>";
                  echo "<input type='submit' name='rent' value='Арендовать'>";
                  echo "</form>";
              }


          }
        }


    } elseif (isset($book_not_found)) {
        echo "<p>Книга не найдена.</p>";
    }
    ?>

    <a href="<?php echo ($_SESSION['username'] === 'Admin') ? 'admin.php' : 'user.php'; ?>">Назад</a>

</body>
</html>
