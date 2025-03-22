<?php
include "./components/core.php";
include "./components/view_header.php";

// Получаем идентификатор аудиокниги из URL
$audiobook_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Получаем информацию об аудиокниге из базы данных
$sql = "SELECT * FROM senior_audiobooks WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $audiobook_id);
$stmt->execute();
$result = $stmt->get_result();
$audiobook = $result->fetch_assoc();

// Проверяем, найдена ли аудиокнига
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

        <!-- <audio controls>
            <source src="<?php 
            // echo htmlspecialchars($audiobook['audio_url']); 
            ?>
            " type="audio/mpeg">
            Ваш браузер не поддерживает элемент audio.
        </audio> -->
<div class="btn" >
        <p><a href="<?php echo htmlspecialchars($audiobook['audio_url']); ?>" >Слушать аудиокнигу</a></p>

        <p><a href="audiobooks/<?php echo htmlspecialchars($audiobook['audio_url2']); ?>" download>Скачать аудиокнигу</a></p>
</div>
        </div>

    </div>

</div>

<span class="arrow"> <a href="senior.php#audio">Назад к списку аудиокниг</a> </span>

</div>
</main>

<?php include 'footer.php' ?>
