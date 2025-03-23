<?php
include "./components/core.php";
include "./components/view_header.php";

// Получаем идентификатор стиха из URL
$poem_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Получаем информацию о стихе из базы данных
$sql = "SELECT * FROM middle_poems WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $poem_id);
$stmt->execute();
$result = $stmt->get_result();
$poem = $result->fetch_assoc();

// Проверяем, найден ли Стих/cборник
if (!$poem) {
    echo "Сборник не найден.";
    exit;
}
?>
<link rel="stylesheet" href="./styles/view_poem.css">
<main>
<div class="container">
<div class="poem">
    <div class="poem-details">
        <img src="image/<?php echo htmlspecialchars($poem['image_url']); ?>" alt="<?php echo htmlspecialchars($poem['title']); ?>">
        <div class="zag">
        <h1><?php echo htmlspecialchars($poem['title']); ?></h1>
        <p><?php echo htmlspecialchars($poem['description']); ?></p>
        </div>
    </div>
    <div class="download-links">
        <h3>Скачать:</h3>
        <ul>
            <?php if (!empty($poem['pdf_url'])): ?>
                <li><a href="poems/<?php echo htmlspecialchars($poem['pdf_url']); ?>" download>PDF</a></li>
            <?php endif; ?>
            <?php if (!empty($poem['epub_url'])): ?>
                <li><a href="poems/<?php echo htmlspecialchars($poem['epub_url']); ?>" download>EPUB</a></li>
            <?php endif; ?>
            <?php if (!empty($poem['fb2_url'])): ?>
                <li><a href="poems/<?php echo htmlspecialchars($poem['fb2_url']); ?>" download>FB2</a></li>
            <?php endif; ?>
            <?php if (!empty($poem['txt_url'])): ?>
                <li><a href="poems/<?php echo htmlspecialchars($poem['txt_url']); ?>" download>TXT</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<span class="arrow"> <a href="middle.php#poem">Назад к списку поэзии</a> </span>
</div>
</main>
<?php include 'footer.php'; ?>
