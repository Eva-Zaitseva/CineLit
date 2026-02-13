<?php
include "./components/core.php";
include "./components/view_header.php";

$movie_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$from = isset($_GET['from']) ? $_GET['from'] : '';

$table = '';
switch($from) {
    case 'primary':
        $table = 'primary_movies';
        break;
    case 'middle':
        $table = 'middle_movies';
        break;
    case 'senior':
        $table = 'senior_movies';
        break;
    default:
        echo "Неверный параметр источника.";
        exit;
}

$sql = "SELECT * FROM $table WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

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
        <iframe src="<?php echo htmlspecialchars($movie['video_url']); ?>" frameborder="0" allow="autoplay; encrypted-media; fullscreen; picture-picture; screen-wake-lock;" allowfullscreen></iframe>
    </div>
</div>
<span class="arrow"> <a href="movie.php#movies">Назад к списку фильмов</a> </span>
</div>
</main>

<?php include 'footer.php' ?>