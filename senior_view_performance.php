<?php
include "./components/core.php";
include "./components/view_header.php";

$performance_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql2 = "SELECT * FROM senior_performances WHERE id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $performance_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$performance = $result2->fetch_assoc();

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

   

    <span class="arrow"> <a href="senior.php#performances">Назад к списку Спектаклей</a> </span>

</div>
</main>

<?php include 'footer.php' ?>