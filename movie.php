<?php
include './components/core.php';
include './components/header.php';

$tables = [
    'primary' => [
        'table' => 'primary_movies',
        'view_page' => 'view_movie.php'
    ],
    'middle' => [
        'table' => 'middle_movies',
        'view_page' => 'view_movie.php'
    ],
    'senior' => [
        'table' => 'senior_movies',
        'view_page' => 'view_movie.php'
    ]
];

// Получаем начальные данные (при первой загрузке страницы)
$results = [];
foreach ($tables as $type => $data) {
    $table = $data['table'];
    $sql = "SELECT * FROM $table";
    $results[$type] = $conn->query($sql);
}
?>

<link rel="stylesheet" href="./styles/movie_all.css">
<style>
    html {
        scroll-behavior: smooth; 
    }
    .no-results {
        text-align: center;
        padding: 40px;
        font-size: 18px;
        color: #666;
        width: 100%;
    }
    .class_btn.active {
        background-color: #8c6a4f;
        color: white;
    }
    .loading {
        text-align: center;
        padding: 20px;
        font-size: 18px;
    }
</style>
<main>
<div class="container">
<h3>ФИЛЬМЫ ДЛЯ ВСЕХ КЛАССОВ</h3>
<div class="full-poisk">

<!-- Кнопки для выбора класса -->
<div class="class_buttons">
    <?php for ($i = 1; $i <= 11; $i++): ?>
        <button type="button" class="class_btn" data-class="<?= $i ?>"><?= $i ?> класс</button>
    <?php endfor; ?>
    <button type="button" class="class_btn active" data-class="">Все классы</button>
</div>

<div class="fil_poisk">
    <form id="searchForm" class="poisk">
        <input type="text" name="search" id="searchInput" placeholder="Поиск...">
        <button type="submit" class="poisk_btn">Найти</button>
    </form>
</div>

</div>
</div>
<div class="cinema_container">
    <!-- Блок фильмов будет загружен через AJAX -->
    <div id="moviesContainer" class="all_cinema">
        <?php
        $hasMovies = false;
        foreach ($tables as $type => $data) {
            if (isset($results[$type]) && $results[$type]->num_rows > 0) {
                $hasMovies = true;
                break;
            }
        }
        if ($hasMovies): ?>
            <?php
            foreach ($tables as $type => $data) {
                if (isset($results[$type]) && $results[$type]->num_rows > 0) {
                    while ($movie = $results[$type]->fetch_assoc()) {
                        echo '<a href="' . $data['view_page'] . '?id=' . $movie['id'] . '&from=' . $type . '" class="movie" title="' . htmlspecialchars($movie['title']) . '">';
                        $imagePath = 'image/' . htmlspecialchars($movie['image_url']);
                        
                        $classLabel = '';
                        if ($type == 'primary') {
                            $classLabel = 'Нач. школа (' . $movie['class'] . ' кл.)';
                        } elseif ($type == 'middle') {
                            $classLabel = 'Ср. школа (' . $movie['class'] . ' кл.)';
                        } else {
                            $classLabel = 'Ст. школа (' . $movie['class'] . ' кл.)';
                        }
                        
                        echo '<div class="movie-card-wrapper">';
                        echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($movie['title']) . '">';
                        echo '<span class="class-label">' . $classLabel . '</span>';
                        echo '<div class="movie-title-container">';
                        echo '<span class="movie-title">' . htmlspecialchars($movie['title']) . '</span>';
                        echo '<span class="movie-title-full">' . htmlspecialchars($movie['title']) . '</span>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                    }
                }
            }
            ?>
        <?php else: ?>
            <div class="no-results">Фильмы не найдены</div>
        <?php endif; ?>
    </div>
</div>
</main>

<!-- Кнопка "Наверх" -->
<button id="scrollToTopBtn" title="Наверх">↑</button>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Текущие параметры фильтрации
    let currentClass = '';
    let currentSearch = '';

    // Функция загрузки фильмов
    function loadMovies() {
        $('#moviesContainer').html('<div class="loading">Загрузка...</div>');
        
        $.ajax({
            url: 'movie_ajax.php',
            type: 'GET',
            data: {
                class: currentClass,
                search: currentSearch
            },
            success: function(response) {
                $('#moviesContainer').html(response);
            },
            error: function() {
                $('#moviesContainer').html('<div class="error">Ошибка загрузки данных</div>');
            }
        });
    }

    // Обработчик кнопок классов
    $('.class_btn').click(function() {
        $('.class_btn').removeClass('active');
        $(this).addClass('active');
        currentClass = $(this).data('class');
        loadMovies();
    });

    // Обработчик формы поиска
    $('#searchForm').submit(function(e) {
        e.preventDefault();
        currentSearch = $('#searchInput').val();
        loadMovies();
    });

    // Кнопка "Наверх"
    const scrollToTopBtn = document.getElementById("scrollToTopBtn");
    window.onscroll = function() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            scrollToTopBtn.style.display = "block";
        } else {
            scrollToTopBtn.style.display = "none";
        }
    };
    scrollToTopBtn.addEventListener("click", function() {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
});
</script>

<?php include 'footer.php'; ?>