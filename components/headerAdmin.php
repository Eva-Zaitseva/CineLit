<?php
// include 'core.php';
// if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
//     header("Location: admin_auto.php"); // Перенаправление на страницу авторизации
//     exit();
// }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/style.css">
    <title>CineLit</title>
</head>
<body>
    <header>
        <div class="header">
            <div class="logo">
                <img src="/image/logo.jpg" alt="logo" width="230px" onclick="window.location.href='index.php?logout=1'" style="cursor: pointer;">
            </div>

            <div class="navigation">
                <nav>
                    <ul>
                        <li><a href="movie.php">ФИЛЬМЫ</a></li>
                        <li><a href="spect.php">СПЕКТАКЛИ</a></li>
                        <li><a href="poetes.php">ПОЭЗИЯ</a></li>
                        <li><a href="audiobooks.php">АУДИОКНИГИ</a></li>
                    </ul>
                </nav>
            </div>


        </div>
    </header>
</body>
</html>