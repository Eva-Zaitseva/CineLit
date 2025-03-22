<?php
include './components/core.php';
include './components/header.php';

$sql = "SELECT * FROM movies";
$result = $conn->query($sql);
?>

<link rel="stylesheet" href="./styles/movie.css">

<main>
<div class="cinema_container">

<h1>ФИЛЬМЫ</h1>

<div class="fil_poisk">
    <a href="">Сортировка</a>

    <div class="poisk">
        
    

            <input type="text" name="search" placeholder="Название фильма..." required>
                <button class="poisk_btn"  style="cursor: pointer;">Найти</button>
   </div>

</div>


<div class="all_cinema">



<?php
        // Проверяем, есть ли фильмы
        if ($result->num_rows > 0) {
            // Выводим данные каждого фильма
            while ($movie = $result->fetch_assoc()) {
                echo '<div class="movie">';
                
                // Формируем путь к изображению
                $imagePath = 'image/' . htmlspecialchars($movie['image_url']);
                
                echo '<a href="view.php?id=' . $movie['id'] . '">';
                echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($movie['title']) . '">';
                echo '</a>';
                
                echo '<a href="view.php?id=' . $movie['id'] . '">' . htmlspecialchars($movie['title']) . '</a>';
                
                // echo '<p>' . htmlspecialchars($movie['description']) . '</p>';
                // echo '<a href="view.php?id=' . $movie['id'] . '">Смотреть видео</a>';
                echo '</div>';
            }
        } else {
            echo "Фильмы не найдены.";
        }
        ?>
        </div>
</div>
</div>
</main>

<?php include 'footer.php'; ?>
