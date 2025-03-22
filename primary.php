<?php
include './components/core.php';
include './components/header.php';

$searchQuery = isset($_POST['search']) ? $conn->real_escape_string($_POST['search']) : '';
$classFilter = isset($_POST['class']) ? $conn->real_escape_string($_POST['class']) : '';

$tables = [
    'movies' => 'primary_movies',
    'performances' => 'primary_performances',
    'poems' => 'primary_poems',
    'audio' => 'primary_audiobooks'
];

$results = [];

// Поиск по всем категориям
if (!empty($searchQuery)) {
    foreach ($tables as $type => $table) {
        $sql = "SELECT * FROM $table WHERE title LIKE '%$searchQuery%'";
        $results[$type] = $conn->query($sql);
    }
}

// Сортировка по классам
if ($classFilter) {
    foreach ($tables as $type => $table) {
        $sql = "SELECT * FROM $table WHERE class = '$classFilter'";
        $results[$type] = $conn->query($sql);
    }
} else {
    foreach ($tables as $type => $table) {
        if (!isset($results[$type])) {
            $sql = "SELECT * FROM $table";
            $results[$type] = $conn->query($sql);
        }
    }
}
?>

<link rel="stylesheet" href="./styles/primary.css">

<main>
<div class="container">
<h3>1 - 4 КЛАСС</h3>
<div class="full-poisk">

<!-- Кнопки для выбора класса -->
    <form action="primary.php" method="POST" class="class_buttons">
        <button type="submit" name="class" value="1" class="class_btn <?= $classFilter == '1' ? 'active' : '' ?>">1 класс</button>
        <button type="submit" name="class" value="2" class="class_btn <?= $classFilter == '2' ? 'active' : '' ?>">2 класс</button>
        <button type="submit" name="class" value="3" class="class_btn <?= $classFilter == '3' ? 'active' : '' ?>">3 класс</button>
        <button type="submit" name="class" value="4" class="class_btn <?= $classFilter == '4' ? 'active' : '' ?>">4 класс</button>
        <button type="submit" name="class" value="" class="class_btn <?= empty($classFilter) ? 'active' : '' ?>">Все классы</button>
    </form>

    <div class="fil_poisk">
        <form action="primary.php" method="post" class="poisk">
            <input type="text" name="search" placeholder="Поиск..." required>
            <button class="poisk_btn" style="cursor: pointer;">Найти</button>
        </form>

        <!-- Кнопка "Вернуться к полному списку" -->
        <?php 
        // if (!empty($searchQuery) || !empty($classFilter)): 
            ?>
            <!-- <form action="primary.php" method="post" class="reset_form">
                <input type="hidden" name="reset" value="1">
                <button type="submit" class="reset_btn" style="cursor: pointer;">Вернуться к полному списку</button>
            </form> -->
        <?php 
        // endif; 
        ?>
    </div>

</div>
</div>
<div class="cinema_container">
    <!-- Блок фильмов -->
    <?php if (isset($results['movies']) && $results['movies']->num_rows > 0): ?>
        <h1 id="movies">ФИЛЬМЫ</h1>
        <div class="all_cinema">
        <?php
        while ($movie = $results['movies']->fetch_assoc()) {
            echo '<div class="movie">';
            $imagePath = 'image/' . htmlspecialchars($movie['image_url']);
            echo '<a href="primary_view.php?id=' . $movie['id'] . '">';
            echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($movie['title']) . '">';
            echo '</a>';
            echo '<a href="primary_view.php?id=' . $movie['id'] . '">' . htmlspecialchars($movie['title']) . '</a>';
            echo '</div>';
        }
        ?>
        </div>
    <?php endif; ?>
</div>

<!-- Блок спектаклей -->
<?php if (isset($results['performances']) && $results['performances']->num_rows > 0): ?>
    <div class="spect_container" id="performance">
        <h1>СПЕКТАКЛИ</h1>
        <div class="all_cinema">
        <?php
        while ($performance = $results['performances']->fetch_assoc()) {
            echo '<div class="movie">';
            $imagePath = 'image/' . htmlspecialchars($performance['image_url']);
            echo '<a href="primary_view_performance.php?id=' . $performance['id'] . '">';
            echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($performance['title']) . '">';
            echo '</a>';
            echo '<a href="primary_view_performance.php?id=' . $performance['id'] . '">' . htmlspecialchars($performance['title']) . '</a>';
            echo '</div>';
        }
        ?>
        </div>
    </div>
<?php endif; ?>

<!-- Блок стихов -->
<?php if (isset($results['poems']) && $results['poems']->num_rows > 0): ?>
    <div class="poem_container" id="poem">
        <h1>ПОЭЗИЯ</h1>
        <div class="all_cinema">
        <?php
        while ($poem = $results['poems']->fetch_assoc()) {
            echo '<div class="movie">';
            $imagePath = 'image/' . htmlspecialchars($poem['image_url']);
            echo '<a href="primary_view_poem.php?id=' . $poem['id'] . '">';
            echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($poem['title']) . '">';
            echo '</a>';
            echo '<a href="primary_view_poem.php?id=' . $poem['id'] . '">' . htmlspecialchars($poem['title']) . '</a>';
            echo '</div>';
        }
        ?>
        </div>
    </div>
<?php endif; ?>

<!-- Блок аудиокниг -->
<?php if (isset($results['audio']) && $results['audio']->num_rows > 0): ?>
    <div class="audio_container" id="audio">
        <h1>АУДИОКНИГИ</h1>
        <div class="all_cinema">
        <?php
        while ($audiobook = $results['audio']->fetch_assoc()) {
            echo '<div class="movie">';
            $imagePath = 'image/' . htmlspecialchars($audiobook['image_url']);
            echo '<a href="primary_view_audiobooks.php?id=' . $audiobook['id'] . '">';
            echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($audiobook['title']) . '">';
            echo '</a>';
            echo '<a href="primary_view_audiobooks.php?id=' . $audiobook['id'] . '">' . htmlspecialchars($audiobook['title']) . '</a>';
            echo '</div>';
        }
        ?>
        </div>
    </div>
<?php endif; ?>

</main>

<?php include 'footer.php'; ?>