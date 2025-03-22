<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <!-- <link rel="stylesheet" href="style/primary.css"> -->
    <title>CineLit</title>
</head>
<body>
    <header>
        <div class="header">
        <div class="logo">
            <img src="image/logo.jpg" alt="logo" width="230px" onclick="window.location.href='index.php'" style="cursor: pointer;">
        </div>

        <!-- <div class="nav_btn"> -->
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
        <div class="btn_p">
        <div class="search-container">
                <input type="text" class="search-input" id="searchQuery" placeholder="Введите ваш запрос..." style="display:none;">
                <button class="search-button" onclick="toggleSearchInput()" style="background-image: url('image/search.svg'); width: 50px; height: 50px; border: none; cursor: pointer;"></button>
            </div>
            <div class="editor">
                        <a href="http://localhost/proekt/admin_panel.php">Редактор</a>
                    </div>
        <!-- </div> -->
    </div>
    </div>
    </header>

    <script>
    function toggleSearchInput() {
        const input = document.getElementById('searchQuery');
        if (input.style.display === 'none' || input.style.display === '') {
            input.style.display = 'block'; // Показываем текстовое поле
            input.focus(); // Устанавливаем фокус на текстовое поле
        } else {
            // Если текстовое поле уже открыто, выполняем поиск
            const query = input.value;
            if (query) {
                window.location.href = `search.php?query=${encodeURIComponent(query)}`; // Перенаправление на страницу поиска
            }
        }
    }
</script>