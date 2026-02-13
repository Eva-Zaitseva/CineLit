<?php
include "components/core.php"; 
include "components/header.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM `users` WHERE `login` = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if ($user['type'] === 'admin' && $user['password'] === $password) {
            $_SESSION['user']['id'] = $user['id'];
            $_SESSION['user']['type'] = $user['type'];
            header("Location: admin_panel.php");
            exit();
        } else {
            $_SESSION['error'] = "Неверный логин или пароль";
        }
    } else {
        $_SESSION['error'] = "Неверный логин или пароль";
    }
    $stmt->close();
}
?>

<link rel="stylesheet" href="styles/admin.css">
<link rel="stylesheet" href="styles/style.css">

<main>
    <form action="" method="POST">
        <div class="auth">
            <div class="auth-block">
                <h3>Авторизация админа</h3>
                <input type="text" name="login" placeholder="Логин" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <p style="color:red;"><?php if (isset($_SESSION['error'])) {
                    echo $_SESSION['error'];
                    unset($_SESSION['error']); 
                } ?></p>
                <button type="submit">Войти</button>
                <button type="button" onclick="window.location.href='index.php'">Отмена</button>
            </div>
        </div>
    </form>
</main>
<? include 'footer.php'; ?>
