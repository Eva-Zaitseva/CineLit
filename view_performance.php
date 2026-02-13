<?php
include "./components/core.php";
include "./components/view_header.php";

$performance_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$from = isset($_GET['from']) ? $_GET['from'] : '';

$table = '';
switch($from) {
    case 'primary':
        $table = 'primary_performances';
        break;
    case 'middle':
        $table = 'middle_performances';
        break;
    case 'senior':
        $table = 'senior_performances';
        break;
    default:
        echo "Неверный параметр источника.";
        exit;
}

$sql = "SELECT * FROM $table WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $performance_id);
$stmt->execute();
$result = $stmt->get_result();
$performance = $result->fetch_assoc();

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
        <iframe src="<?php echo htmlspecialchars($performance['video_url']); ?>" frameborder="0" allow="autoplay; encrypted-media; fullscreen; picture-picture; screen-wake-lock;" allowfullscreen></iframe>
    </div>
</div>
<span class="arrow"> <a href="spect.php#performances">Назад к списку спектаклей</a> </span>
</div>
</main>

<?php include 'footer.php' ?>