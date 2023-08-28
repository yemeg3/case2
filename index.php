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


if (isset($_POST['login'])) {
    $login_username = $_POST['username'];
    $login_password = $_POST['password'];

    $login_sql = "SELECT * FROM users WHERE username = '$login_username'";
    $login_result = $conn->query($login_sql);

    if ($login_result->num_rows > 0) {
        $user_data = $login_result->fetch_assoc();
        if (password_verify($login_password, $user_data['password'])) {
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['username'] = $user_data['username'];

            if ($user_data['username'] === 'Admin') {
                header("Location: admin.php");

            } else {
                header("Location: user.php");
            }
            exit();
        } else {
            $login_error = "Invalid login credentials.";
        }
    } else {
        $login_error = "Invalid login credentials.";
    }
}


if (isset($_POST['register'])) {
    $register_username = $_POST['new_username'];
    $register_password = $_POST['new_password'];


    $hashed_password = password_hash($register_password, PASSWORD_DEFAULT);


    $register_sql = "INSERT INTO users (username, password) VALUES ('$register_username', '$hashed_password')";
    if ($conn->query($register_sql) === TRUE) {
        $register_success = "Registration successful. You can now log in.";
    } else {
        $register_error = "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход и регистрация</title>
</head>
<body>
    <h1>Вход и регистрация</h1>
    <h2>Вход</h2>
    <h2>для админ панели: Admin:Admin</h2>
    <h2>Пользователь, с арендованной книгой, до конца аренды который меньше 10 дней: Валерий:vostcorp12Qaq</h2>
    <form method="post">
        <label>Логин: <input type="text" name="username"></label><br>
        <label>Пароль: <input type="password" name="password"></label><br>
        <input type="submit" name="login" value="Войти">
    </form>

    <h2>Регистрация</h2>
    <form method="post">
        <label>Придумайте Логин: <input type="text" name="new_username"></label><br>
        <label>Придумайте Пароль: <input type="password" name="new_password"></label><br>
        <input type="submit" name="register" value="Зарегистрироваться">
    </form>

    <?php
    if (isset($login_error)) {
        echo "<p style='color: red;'>$login_error</p>";
    }

    if (isset($register_success)) {
        echo "<p style='color: green;'>$register_success</p>";
    } elseif (isset($register_error)) {
        echo "<p style='color: red;'>$register_error</p>";
    }
    ?>
</body>
</html>
