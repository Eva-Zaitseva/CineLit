<?php
include "./components/core.php"; // Подключаем базу данных
include "./components/header.php"; // Подключаем заголовок


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Подготовленный запрос для предотвращения SQL-инъекций
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE `login` = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Проверяем совпадение пароля
        if ($user['type'] === 'admin' && $user['password'] === $password) {
            // Успешная авторизация
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

<main>
    <form action="" method="POST">
        <div class="auth">
            <div class="auth-block">
                <h3>Авторизация админа</h3>
                <input type="text" name="login" placeholder="Логин" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <p style="color:red;"><?php if (isset($_SESSION['error'])) {
                    echo $_SESSION['error'];
                    unset($_SESSION['error']); // Очищаем сообщение об ошибке после его отображения
                } ?></p>
                <button type="submit">Войти</button>
                <button type="button" onclick="window.location.href='index.php'">Отмена</button>
            </div>
        </div>
    </form>
</main>
</body>
</html>
