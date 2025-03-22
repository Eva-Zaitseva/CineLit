<?php
include "./components/core.php";
include "./components/view_header.php";

// Получаем идентификатор Спектакля из URL
$performance_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Получаем информацию о Спектакле из базы данных
// $sql = "SELECT * FROM movies WHERE id = ?";
$sql2 = "SELECT * FROM middle_performances WHERE id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $performance_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$performance = $result2->fetch_assoc();

// Проверяем, найден ли Спектакль
if (!$performance) {
    echo "Спектакль не найден.";
    exit;
}


?>

<main>
<div class="container">

<div class="movie">

    <div class="film">

        <img src="image/<?php echo htmlspecialchars($performance['image_url']); ?>" alt="<?php echo htmlspecialchars($performance['title']); ?>">
        
        <div class="zag">

        <h1><?php echo htmlspecialchars($performance['title']); ?></h1>
        <p><?php echo htmlspecialchars($performance['description']); ?></p>

    </div>

    </div>

    <div class="video-container">
            <iframe  src="<?php echo htmlspecialchars($performance['video_url']); ?>" frameborder="0" allow="autoplay; encrypted-media; fullscreen; picture-picture; screen-wake-lock;" allowfullscreen></iframe>
    </div>

</div>

   

    <span class="arrow"> <a href="middle.php#performance">Назад к списку Спектаклей</a> </span>

</div>
</main>

<?php include 'footer.php' ?>