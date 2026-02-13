<?php
include "./components/core.php";
include "./components/view_header.php";

$poem_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM senior_poems WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $poem_id);
$stmt->execute();
$result = $stmt->get_result();
$poem = $result->fetch_assoc();

if (!$poem) {
    echo "Стих/сборник не найден.";
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
                    <li><a href="<?php echo htmlspecialchars($poem['pdf_url']); ?>" download>PDF</a></li>
                <?php endif; ?>
                <?php if (!empty($poem['epub_url'])): ?>
                    <li><a href="<?php echo htmlspecialchars($poem['epub_url']); ?>" download>EPUB</a></li>
                <?php endif; ?>
                <?php if (!empty($poem['fb2_url'])): ?>
                    <li><a href="<?php echo htmlspecialchars($poem['fb2_url']); ?>" download>FB2</a></li>
                <?php endif; ?>
                <?php if (!empty($poem['txt_url'])): ?>
                    <li><a href="<?php echo htmlspecialchars($poem['txt_url']); ?>" download>TXT</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <span class="arrow"> <a href="senior.php#poems">Назад к списку сборников стихов</a> </span>
</div>
</main>

<?php include 'footer.php'; ?>