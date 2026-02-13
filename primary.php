<?php
include './components/core.php';
include './components/header.php';

// Параметры пагинации для каждого раздела
$itemsPerPage = 12;
$pageMovies = isset($_GET['page_movies']) ? (int)$_GET['page_movies'] : 1;
$pagePerformances = isset($_GET['page_performances']) ? (int)$_GET['page_performances'] : 1;
$pagePoems = isset($_GET['page_poems']) ? (int)$_GET['page_poems'] : 1;
$pageAudio = isset($_GET['page_audio']) ? (int)$_GET['page_audio'] : 1;

$offsetMovies = ($pageMovies - 1) * $itemsPerPage;
$offsetPerformances = ($pagePerformances - 1) * $itemsPerPage;
$offsetPoems = ($pagePoems - 1) * $itemsPerPage;
$offsetAudio = ($pageAudio - 1) * $itemsPerPage;

$searchQuery = isset($_POST['search']) ? $conn->real_escape_string($_POST['search']) : '';
$classFilter = isset($_POST['class']) ? $conn->real_escape_string($_POST['class']) : '';

$tables = [
    'movies' => [
        'table' => 'primary_movies',
        'view_page' => 'primary_view.php'
    ],
    'performances' => [
        'table' => 'primary_performances',
        'view_page' => 'primary_view_performance.php'
    ],
    'poems' => [
        'table' => 'primary_poems',
        'view_page' => 'primary_view_poem.php'
    ],
    'audio' => [
        'table' => 'primary_audiobooks',
        'view_page' => 'primary_view_audiobooks.php'
    ]
];

$results = [];
$totalItems = [];

// Поиск по всем категориям
if (!empty($searchQuery)) {
    foreach ($tables as $type => $data) {
        $table = $data['table'];
        $offset = ${'offset' . ucfirst($type)};
        $sql = "SELECT * FROM $table WHERE title LIKE '%$searchQuery%' LIMIT $itemsPerPage OFFSET $offset";
        $results[$type] = $conn->query($sql);

        $countSql = "SELECT COUNT(*) as total FROM $table WHERE title LIKE '%$searchQuery%'";
        $totalItems[$type] = $conn->query($countSql)->fetch_assoc()['total'];
    }
}

// Сортировка по классам
if ($classFilter) {
    foreach ($tables as $type => $data) {
        $table = $data['table'];
        $offset = ${'offset' . ucfirst($type)};
        $sql = "SELECT * FROM $table WHERE class = '$classFilter' LIMIT $itemsPerPage OFFSET $offset";
        $results[$type] = $conn->query($sql);

        $countSql = "SELECT COUNT(*) as total FROM $table WHERE class = '$classFilter'";
        $totalItems[$type] = $conn->query($countSql)->fetch_assoc()['total'];
    }
} else {
    foreach ($tables as $type => $data) {
        if (!isset($results[$type])) {
            $table = $data['table'];
            $offset = ${'offset' . ucfirst($type)};
            $sql = "SELECT * FROM $table LIMIT $itemsPerPage OFFSET $offset";
            $results[$type] = $conn->query($sql);

            $countSql = "SELECT COUNT(*) as total FROM $table";
            $totalItems[$type] = $conn->query($countSql)->fetch_assoc()['total'];
        }
    }
}
?>

<link rel="stylesheet" href="./styles/primary.css">
<style>
    html { scroll-behavior: smooth; }
    .pagination {
        display: flex;
        justify-content: center;
        margin: 20px 0;
        flex-wrap: wrap;
    }
    .page-link {
        padding: 8px 15px;
        margin: 0 5px;
        border: 1px solid #ddd;
        background: #f9f9f9;
        color: #333;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.3s;
    }
    .page-link:hover { background: #eee; }
    .page-link.active {
        background: #375137;
        color: white;
        border-color: #375137;
    }
    .loading, .error {
        text-align: center;
        padding: 20px;
        font-size: 18px;
        color: #666;
    }
    .error { color: #dc3545; }
    .no-results {
        text-align: center;
        padding: 40px;
        font-size: 18px;
        color: #666;
        width: 100%;
    }


</style>

<main>
<div class="container">
<h3>1 - 4 КЛАСС</h3>
<div class="full-poisk">
    <div class="class_buttons">
        <button type="button" class="class_btn <?= $classFilter == '1' ? 'active' : '' ?>" data-class="1">1 класс</button>
        <button type="button" class="class_btn <?= $classFilter == '2' ? 'active' : '' ?>" data-class="2">2 класс</button>
        <button type="button" class="class_btn <?= $classFilter == '3' ? 'active' : '' ?>" data-class="3">3 класс</button>
        <button type="button" class="class_btn <?= $classFilter == '4' ? 'active' : '' ?>" data-class="4">4 класс</button>
        <button type="button" class="class_btn <?= empty($classFilter) ? 'active' : '' ?>" data-class="">Все классы</button>
    </div>

    <div class="fil_poisk">
        <form action="primary.php" method="post" class="poisk">
            <input type="text" name="search" placeholder="Поиск..." required>
            <button class="poisk_btn" style="cursor: pointer;">Найти</button>
        </form>
    </div>
</div>
</div>

<!-- Блок фильмов -->
<?php if (isset($results['movies'])): ?>
    <div class="cinema_container" id="movies"> 
        <h1>ФИЛЬМЫ</h1>
        <div class="all_cinema">
        <?php if ($results['movies']->num_rows > 0): ?>
            <?php while ($movie = $results['movies']->fetch_assoc()): ?>
    <a href="<?= $tables['movies']['view_page'] ?>?id=<?= $movie['id'] ?>" class="movie" title="<?= htmlspecialchars($movie['title']) ?>">
        <div class="movie-card-wrapper">
            <img src="image/<?= htmlspecialchars($movie['image_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
            <span class="class-label"><?= $movie['class'] ?> класс</span>
            <div class="movie-title-container">
                <span class="movie-title"><?= htmlspecialchars($movie['title']) ?></span>
                <span class="movie-title-full"><?= htmlspecialchars($movie['title']) ?></span>
            </div>
        </div>
    </a>
<?php endwhile; ?>
        <?php else: ?>
            <div class="no-results">По вашему запросу ничего не найдено</div>
        <?php endif; ?>
        </div>
        <?php
        $totalPages = ceil($totalItems['movies'] / $itemsPerPage);
        if ($totalPages > 1): ?>
            <div class="pagination movies-pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <button class="page-link <?= $pageMovies == $i ? 'active' : '' ?>" 
                            data-page="<?= $i ?>" 
                            data-type="movies"
                            data-class="<?= $classFilter ?>"
                            data-search="<?= $searchQuery ?>"><?= $i ?></button>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Блок спектаклей -->
<?php if (isset($results['performances'])): ?>
    <div class="spect_container" id="performances"> 
        <h1>СПЕКТАКЛИ</h1>
        <div class="all_cinema">
        <?php if ($results['performances']->num_rows > 0): ?>
            <?php while ($performance = $results['performances']->fetch_assoc()): ?>
                <a href="<?= $tables['performances']['view_page'] ?>?id=<?= $performance['id'] ?>" class="movie" title="<?= htmlspecialchars($performance['title']) ?>">
                    <div class="movie-card-wrapper">
                        <img src="image/<?= htmlspecialchars($performance['image_url']) ?>" alt="<?= htmlspecialchars($performance['title']) ?>">
                        <span class="class-label"><?= $performance['class'] ?> класс</span>
                        <div class="movie-title-container">
                            <span class="movie-title"><?= htmlspecialchars($performance['title']) ?></span>
                            <span class="movie-title-full"><?= htmlspecialchars($performance['title']) ?></span>
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-results">По вашему запросу ничего не найдено</div>
        <?php endif; ?>
        </div>
        <?php
        $totalPages = ceil($totalItems['performances'] / $itemsPerPage);
        if ($totalPages > 1): ?>
            <div class="pagination performances-pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <button class="page-link <?= $pagePerformances == $i ? 'active' : '' ?>" 
                            data-page="<?= $i ?>" 
                            data-type="performances"
                            data-class="<?= $classFilter ?>"
                            data-search="<?= $searchQuery ?>"><?= $i ?></button>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Блок стихов -->
<?php if (isset($results['poems'])): ?>
    <div class="poem_container" id="poems"> 
        <h1>ПОЭЗИЯ</h1>
        <div class="all_cinema">
        <?php if ($results['poems']->num_rows > 0): ?>
            <?php while ($poem = $results['poems']->fetch_assoc()): ?>
                <a href="<?= $tables['poems']['view_page'] ?>?id=<?= $poem['id'] ?>" class="movie" title="<?= htmlspecialchars($poem['title']) ?>">
                    <div class="movie-card-wrapper">
                        <img src="image/<?= htmlspecialchars($poem['image_url']) ?>" alt="<?= htmlspecialchars($poem['title']) ?>">
                        <span class="class-label"><?= $poem['class'] ?> класс</span>
                        <div class="movie-title-container">
                            <span class="movie-title"><?= htmlspecialchars($poem['title']) ?></span>
                            <span class="movie-title-full"><?= htmlspecialchars($poem['title']) ?></span>
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-results">По вашему запросу ничего не найдено</div>
        <?php endif; ?>
        </div>
        <?php
        $totalPages = ceil($totalItems['poems'] / $itemsPerPage);
        if ($totalPages > 1): ?>
            <div class="pagination poems-pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <button class="page-link <?= $pagePoems == $i ? 'active' : '' ?>" 
                            data-page="<?= $i ?>" 
                            data-type="poems"
                            data-class="<?= $classFilter ?>"
                            data-search="<?= $searchQuery ?>"><?= $i ?></button>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Блок аудиокниг -->
<?php if (isset($results['audio'])): ?>
    <div class="audio_container" id="audio"> 
        <h1>АУДИОКНИГИ</h1>
        <div class="all_cinema">
        <?php if ($results['audio']->num_rows > 0): ?>
            <?php while ($audiobook = $results['audio']->fetch_assoc()): ?>
                <a href="<?= $tables['audio']['view_page'] ?>?id=<?= $audiobook['id'] ?>" class="movie" title="<?= htmlspecialchars($audiobook['title']) ?>">
                    <div class="movie-card-wrapper">
                        <img src="image/<?= htmlspecialchars($audiobook['image_url']) ?>" alt="<?= htmlspecialchars($audiobook['title']) ?>">
                        <span class="class-label"><?= $audiobook['class'] ?> класс</span>
                        <div class="movie-title-container">
                            <span class="movie-title"><?= htmlspecialchars($audiobook['title']) ?></span>
                            <span class="movie-title-full"><?= htmlspecialchars($audiobook['title']) ?></span>
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-results">По вашему запросу ничего не найдено</div>
        <?php endif; ?>
        </div>
        <?php
        $totalPages = ceil($totalItems['audio'] / $itemsPerPage);
        if ($totalPages > 1): ?>
            <div class="pagination audio-pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <button class="page-link <?= $pageAudio == $i ? 'active' : '' ?>" 
                            data-page="<?= $i ?>" 
                            data-type="audio"
                            data-class="<?= $classFilter ?>"
                            data-search="<?= $searchQuery ?>"><?= $i ?></button>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

</main>

<!-- Кнопка "Наверх" -->
<button id="scrollToTopBtn" title="Наверх">↑</button>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Получаем кнопку
    const scrollToTopBtn = document.getElementById("scrollToTopBtn");

    // Показываем кнопку, когда пользователь прокрутил страницу вниз на 20px
    window.onscroll = function() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            scrollToTopBtn.style.display = "block";
        } else {
            scrollToTopBtn.style.display = "none";
        }
    };

    // Плавная прокрутка наверх при клике на кнопку
    scrollToTopBtn.addEventListener("click", function() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });

// Обработчик клика по пагинации
$(document).on('click', '.page-link', function(e) {
    e.preventDefault();
    
    const page = $(this).data('page');
    const type = $(this).data('type');
    const classFilter = $(this).data('class');
    const searchQuery = $(this).data('search');
    
    const $content = $('#' + type).find('.all_cinema');

    // Сохраняем текущее положение прокрутки
    const scrollPosition = $(window).scrollTop();

    // Анимация исчезновения
    $content.fadeOut(500, function() {
        // AJAX-запрос
        $.ajax({
            url: 'primary_ajax.php',
            type: 'GET',
            data: {
                page: page,
                type: type,
                class: classFilter,
                search: searchQuery
            },
            success: function(response) {
                // Если контент пустой, показываем сообщение
                if (response.content.trim() === '') {
                    response.content = '<div class="no-results">По вашему запросу ничего не найдено</div>';
                }
                
                // Обновляем контент
                $content.html(response.content);
                
                // Анимация появления нового контента
                $content.fadeIn(500, function() {
                    // Возвращаем прокрутку на сохранённую позицию
                    $(window).scrollTop(scrollPosition);
                });
                
                // Обновляем пагинацию
                $('.' + type + '-pagination').html(response.pagination);
            },
            error: function(xhr) {
                console.error('Error loading page: ' + xhr.statusText);
                $content.html('<div class="error">Ошибка загрузки данных</div>');
                $content.fadeIn(500); // Показываем сообщение об ошибке
            }
        });
    });
});

// Обработчик формы поиска
$('.poisk').on('submit', function(e) {
    e.preventDefault();
    const searchQuery = $(this).find('input[name="search"]').val();
    const classFilter = $('.class_btn.active').data('class'); // Получаем выбранный класс
    
    // Отправляем AJAX-запрос для каждого раздела
    $('[id^="movies"], [id^="performances"], [id^="poems"], [id^="audio"]').each(function() {
        const type = $(this).attr('id');
        loadSectionData(type, 1, classFilter, searchQuery); // Передаем classFilter в запрос
    });
});

// Обработчик кнопок фильтра по классам
$('.class_btn').on('click', function(e) {
    e.preventDefault();
    const classFilter = $(this).data('class');
    const searchQuery = $('.poisk input[name="search"]').val(); // Получаем текущий поисковый запрос
    
    // Устанавливаем активную кнопку
    $('.class_btn').removeClass('active');
    $(this).addClass('active');
    
    // Отправляем AJAX-запрос для каждого раздела
    $('[id^="movies"], [id^="performances"], [id^="poems"], [id^="audio"]').each(function() {
        const type = $(this).attr('id');
        loadSectionData(type, 1, classFilter, searchQuery); // Передаем текущий поисковый запрос
    });
});

// Функция для загрузки данных раздела
function loadSectionData(type, page, classFilter, searchQuery) {
    const $content = $('#' + type).find('.all_cinema');
    $content.html('<div class="loading">Загрузка...</div>');
    
    $.ajax({
        url: 'primary_ajax.php',
        type: 'GET',
        data: {
            page: page,
            type: type,
            class: classFilter,
            search: searchQuery
        },
        success: function(response) {
            // Если контент пустой, показываем сообщение
            if (response.content.trim() === '') {
                response.content = '<div class="no-results">По вашему запросу ничего не найдено</div>';
            }
            $content.html(response.content);
            $('.' + type + '-pagination').html(response.pagination);
        },
        error: function(xhr) {
            $content.html('<div class="error">Ошибка загрузки данных</div>');
        }
    });
}
</script>