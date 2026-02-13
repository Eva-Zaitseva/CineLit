<?php
include './components/core.php';
include './components/header.php';

$tables = [
    'primary' => [
        'table' => 'primary_audiobooks',
        'view_page' => 'view_audiobooks.php'
    ],
    'middle' => [
        'table' => 'middle_audiobooks',
        'view_page' => 'view_audiobooks.php'
    ],
    'senior' => [
        'table' => 'senior_audiobooks',
        'view_page' => 'view_audiobooks.php'
    ]
];

$results = [];
foreach ($tables as $type => $data) {
    $table = $data['table'];
    $sql = "SELECT * FROM $table";
    $results[$type] = $conn->query($sql);
}
?>

<link rel="stylesheet" href="./styles/audiobooks_all.css">
<style>
    html { scroll-behavior: smooth; }
    .no-results { text-align: center; padding: 40px; font-size: 18px; color: #666; width: 100%; }
    .class_btn.active { background-color: #8c6a4f; color: white; }
    .loading { text-align: center; padding: 20px; font-size: 18px; }
</style>

<main>
<div class="container">
<h3>АУДИОКНИГИ ДЛЯ ВСЕХ КЛАССОВ</h3>
<div class="full-poisk">

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
<div class="audio_container">
    <div id="audiobooksContainer" class="all_cinema">
        <?php
        $hasAudiobooks = false;
        foreach ($tables as $type => $data) {
            if (isset($results[$type]) && $results[$type]->num_rows > 0) {
                $hasAudiobooks = true;
                while ($audiobook = $results[$type]->fetch_assoc()) {
                    echo '<a href="' . $data['view_page'] . '?id=' . $audiobook['id'] . '&from=' . $type . '" class="movie" title="' . htmlspecialchars($audiobook['title']) . '">';
                    echo '<div class="movie-card-wrapper">';
                    echo '<img src="image/' . htmlspecialchars($audiobook['image_url']) . '" alt="' . htmlspecialchars($audiobook['title']) . '">';
                    
                    $classLabel = '';
                    if ($type == 'primary') {
                        $classLabel = 'Нач. школа (' . $audiobook['class'] . ' кл.)';
                    } elseif ($type == 'middle') {
                        $classLabel = 'Ср. школа (' . $audiobook['class'] . ' кл.)';
                    } else {
                        $classLabel = 'Ст. школа (' . $audiobook['class'] . ' кл.)';
                    }
                    
                    echo '<span class="class-label">' . $classLabel . '</span>';
                    echo '<div class="movie-title-container">';
                    echo '<span class="movie-title">' . htmlspecialchars($audiobook['title']) . '</span>';
                    echo '<span class="movie-title-full">' . htmlspecialchars($audiobook['title']) . '</span>';
                    echo '</div></div></a>';
                }
            }
        }
        if (!$hasAudiobooks) echo '<div class="no-results">Аудиокниги не найдены</div>';
        ?>
    </div>
</div>
</main>

<button id="scrollToTopBtn" title="Наверх">↑</button>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let currentClass = '';
    let currentSearch = '';

    function loadAudiobooks() {
        $('#audiobooksContainer').html('<div class="loading">Загрузка...</div>');
        
        $.ajax({
            url: 'audiobooks_ajax.php',
            type: 'GET',
            data: {
                class: currentClass,
                search: currentSearch
            },
            success: function(response) {
                $('#audiobooksContainer').html(response);
            },
            error: function() {
                $('#audiobooksContainer').html('<div class="error">Ошибка загрузки данных</div>');
            }
        });
    }

    $('.class_btn').click(function() {
        $('.class_btn').removeClass('active');
        $(this).addClass('active');
        currentClass = $(this).data('class');
        loadAudiobooks();
    });

    $('#searchForm').submit(function(e) {
        e.preventDefault();
        currentSearch = $('#searchInput').val();
        loadAudiobooks();
    });

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