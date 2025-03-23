<?php
include "./components/core.php";
include "./components/view_header.php";

// Получаем идентификатор фильма из URL
$movie_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Получаем информацию о фильме из базы данных
// $sql = "SELECT * FROM movies WHERE id = ?";
$sql = "SELECT * FROM primary_movies WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

// Проверяем, найден ли фильм
if (!$movie) {
    echo "Фильм не найден.";
    exit;
}
?>
<main>
<div class="container">
<div class="movie">
    <div class="film">
        <img src="image/<?php echo htmlspecialchars($movie['image_url']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
    <div class="zag">
        <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
        <p><?php echo htmlspecialchars($movie['description']); ?></p>
    </div>
    </div>
    <div class="video-container">
            <iframe  src="<?php echo htmlspecialchars($movie['video_url']); ?>" frameborder="0" allow="autoplay; encrypted-media; fullscreen; picture-picture; screen-wake-lock;" allowfullscreen></iframe>
    </div>
</div>
    <span class="arrow"> <a href="primary.php#movies">Назад к списку фильмов</a> </span>
</div>
</main>
<?php include 'footer.php' ?>