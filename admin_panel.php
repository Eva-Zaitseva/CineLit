<?php
include "./components/core.php";
include "./components/header.php";

// Проверка, авторизован ли администратор
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
    header("Location: admin_auto.php");
    exit();
}
?>

<main>
    <div class="admin-panel">
        <h1>Админ панель</h1>
        <div class="admin-options">
            <a href="admin_manage.php?category=movies" class="admin-option">Управление фильмами</a>
            <a href="admin_manage.php?category=performances" class="admin-option">Управление спектаклями</a>
            <a href="admin_manage.php?category=poems" class="admin-option">Управление стихами</a>
            <a href="admin_manage.php?category=audio" class="admin-option">Управление аудиокнигами</a>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>