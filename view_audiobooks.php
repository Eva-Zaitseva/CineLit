<?php
include "./components/core.php";
include "./components/view_header.php";

$audiobook_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$from = isset($_GET['from']) ? $_GET['from'] : '';

$table = '';
switch($from) {
    case 'primary':
        $table = 'primary_audiobooks';
        break;
    case 'middle':
        $table = 'middle_audiobooks';
        break;
    case 'senior':
        $table = 'senior_audiobooks';
        break;
    default:
        echo "Неверный параметр источника.";
        exit;
}

$sql = "SELECT * FROM $table WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $audiobook_id);
$stmt->execute();
$result = $stmt->get_result();
$audiobook = $result->fetch_assoc();

if (!$audiobook) {
    echo "Аудиокнига не найдена.";
    exit;
}
?>
<link rel="stylesheet" href="styles/view_audiobook.css">
<main>
<div class="container">
<div class="audiobook">
    <div class="audio-details">
        <img src="image/<?php echo htmlspecialchars($audiobook['image_url']); ?>" alt="<?php echo htmlspecialchars($audiobook['title']); ?>">  
        <div class="zag">
            <h1><?php echo htmlspecialchars($audiobook['title']); ?></h1>
            <p><?php echo htmlspecialchars($audiobook['description']); ?></p>
            <div class="btn">
                <?php if (!empty($audiobook['audio_url'])): ?>
                    <p><a href="<?php echo htmlspecialchars($audiobook['audio_url']); ?>">Слушать аудиокнигу</a></p>
                <?php endif; ?>
                <?php if (!empty($audiobook['audio_url2'])): ?>
                    <p><a href="audiobooks/<?php echo htmlspecialchars($audiobook['audio_url2']); ?>" download>Скачать аудиокнигу</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<span class="arrow"> <a href="audiobooks.php#audiobooks">Назад к списку аудиокниг</a> </span>
</div>
</main>
<?php include 'footer.php' ?>