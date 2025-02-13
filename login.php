<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "amk_gorizont");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === 'administrator') {
        // Установка данных для администратора
        $_SESSION['user'] = [
            'lastname' => 'Администратор',
            'firstname' => '',
            'middlename' => '',
            'is_admin' => true // Флаг для администратора
        ];
        header("Location: index.php");
        exit();
    }

    // Для обычных пользователей
    $password_hashed = md5($password); // Хэширование пароля
    $stmt = $mysqli->prepare("SELECT lastname, firstname, middlename FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password_hashed);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $user['is_admin'] = false; // Указываем, что это не администратор
        $_SESSION['user'] = $user;
        header("Location: index.php");
    } else {
        echo "<script>alert('Неверный логин или пароль'); window.location.href='index.php';</script>";
    }
}
?>

