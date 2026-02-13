<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/view.css">
    <title>
    CineLit
        <?php 
        echo htmlspecialchars($movie['title']); 
        ?>
    </title>
</head>
<body>
    <header>
        <div class="header">
            <div class="logo">
                <img src="./image/logo.jpg" alt="Логотип CineLit" onclick="window.location.href='index.php'" style="cursor: pointer;">
            </div>

            <div class="humburger" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="navigation">
                <nav>
                    <ul>
                        <li><a href="movie.php">ФИЛЬМЫ</a></li>
                        <li><a href="spect.php">СПЕКТАКЛИ</a></li>
                        <li><a href="poems.php">ПОЭЗИЯ</a></li>
                        <li><a href="audiobooks.php">АУДИОКНИГИ</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <script>
        function toggleMenu() {
            const navigation = document.querySelector('.navigation');
            const humburger = document.querySelector('.humburger');
            navigation.classList.toggle('active');
            humburger.classList.toggle('active');
        }
    </script>
