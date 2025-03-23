<?php
include "./components/core.php";
include "./components/header.php";

// Проверка, авторизован ли администратор
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
    header("Location: admin_auto.php");
    exit();
}

$category = isset($_GET['category']) ? $_GET['category'] : '';
$group = isset($_GET['group']) ? $_GET['group'] : 'primary';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Определение таблиц в зависимости от категории
$tables = [
    'movies' => [
        'primary' => 'primary_movies',
        'middle' => 'middle_movies',
        'senior' => 'senior_movies'
    ],
    'performances' => [
        'primary' => 'primary_performances',
        'middle' => 'middle_performances',
        'senior' => 'senior_performances'
    ],
    'poems' => [
        'primary' => 'primary_poems',
        'middle' => 'middle_poems',
        'senior' => 'senior_poems'
    ],
    'audio' => [
        'primary' => 'primary_audiobooks',
        'middle' => 'middle_audiobooks',
        'senior' => 'senior_audiobooks'
    ]
];

if (!array_key_exists($category, $tables) || !array_key_exists($group, $tables[$category])) {
    die("Неверная категория или группа");
}

$table = $tables[$category][$group];

// Получение данных материала, если это редактирование
$material = null;
if ($id > 0) {
    $sql = "SELECT * FROM $table WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $material = $result->fetch_assoc();
    } else {
        die("Материал не найден");
    }
}

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $class = $conn->real_escape_string($_POST['class']);
    $image_url = $conn->real_escape_string($_POST['image_url']);
    $description = $conn->real_escape_string($_POST['description']);

    // Дополнительные поля в зависимости от категории
    if ($category === 'movies' || $category === 'performances') {
        $video_url = $conn->real_escape_string($_POST['video_url']);
    } elseif ($category === 'poems') {
        $pdf_url = $conn->real_escape_string($_POST['pdf_url']);
        $epub_url = $conn->real_escape_string($_POST['epub_url']);
        $fb2_url = $conn->real_escape_string($_POST['fb2_url']);
        $txt_url = $conn->real_escape_string($_POST['txt_url']);
    } elseif ($category === 'audio') {
        $audio_url = $conn->real_escape_string($_POST['audio_url']);
        $audio_url2 = $conn->real_escape_string($_POST['audio_url2']);
    }

    if ($id > 0) {
        // Редактирование материала
        if ($category === 'movies' || $category === 'performances') {
            $sql = "UPDATE $table SET title='$title', class='$class', image_url='$image_url', video_url='$video_url', description='$description' WHERE id=$id";
        } elseif ($category === 'poems') {
            $sql = "UPDATE $table SET title='$title', class='$class', image_url='$image_url', description='$description', pdf_url='$pdf_url', epub_url='$epub_url', fb2_url='$fb2_url', txt_url='$txt_url' WHERE id=$id";
        } elseif ($category === 'audio') {
            $sql = "UPDATE $table SET title='$title', class='$class', image_url='$image_url', description='$description', audio_url='$audio_url', audio_url2='$audio_url2' WHERE id=$id";
        }
    } else {
        // Добавление нового материала
        if ($category === 'movies' || $category === 'performances') {
            $sql = "INSERT INTO $table (title, class, image_url, video_url, description) VALUES ('$title', '$class', '$image_url', '$video_url', '$description')";
        } elseif ($category === 'poems') {
            $sql = "INSERT INTO $table (title, class, image_url, description, pdf_url, epub_url, fb2_url, txt_url) VALUES ('$title', '$class', '$image_url', '$description', '$pdf_url', '$epub_url', '$fb2_url', '$txt_url')";
        } elseif ($category === 'audio') {
            $sql = "INSERT INTO $table (title, class, image_url, description, audio_url, audio_url2) VALUES ('$title', '$class', '$image_url', '$description', '$audio_url', '$audio_url2')";
        }
    }

    if ($conn->query($sql)) {
        $_SESSION['message'] = "Материал успешно " . ($id > 0 ? "отредактирован" : "добавлен");
        header("Location: admin_manage.php?category=$category&group=$group");
        exit();
    } else {
        $_SESSION['error'] = "Ошибка при сохранении материала";
    }
}
?>

<main>
    <div class="admin-panel">
        <h1><?= $id > 0 ? "Редактирование материала" : "Добавление нового материала" ?></h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <p style="color:red;"><?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST">
            <label for="title">Название:</label>
            <input type="text" name="title" id="title" value="<?= $material ? htmlspecialchars($material['title']) : '' ?>" required>
            
            <label for="class">Класс:</label>
            <input type="text" name="class" id="class" value="<?= $material ? htmlspecialchars($material['class']) : '' ?>" required>
            
            <label for="image_url">URL изображения:</label>
            <input type="text" name="image_url" id="image_url" value="<?= $material ? htmlspecialchars($material['image_url']) : '' ?>" required>
            
            <label for="description">Описание:</label>
            <textarea name="description" id="description" required><?= $material ? htmlspecialchars($material['description']) : '' ?></textarea>

            <!-- Дополнительные поля в зависимости от категории -->
            <?php if ($category === 'movies' || $category === 'performances'): ?>
                <label for="video_url">URL видео:</label>
                <input type="text" name="video_url" id="video_url" value="<?= $material ? htmlspecialchars($material['video_url']) : '' ?>" required>
            <?php elseif ($category === 'poems'): ?>
                <label for="pdf_url">URL PDF:</label>
                <input type="text" name="pdf_url" id="pdf_url" value="<?= $material ? htmlspecialchars($material['pdf_url']) : '' ?>">
                
                <label for="epub_url">URL EPUB:</label>
                <input type="text" name="epub_url" id="epub_url" value="<?= $material ? htmlspecialchars($material['epub_url']) : '' ?>">
                
                <label for="fb2_url">URL FB2:</label>
                <input type="text" name="fb2_url" id="fb2_url" value="<?= $material ? htmlspecialchars($material['fb2_url']) : '' ?>">
                
                <label for="txt_url">URL TXT:</label>
                <input type="text" name="txt_url" id="txt_url" value="<?= $material ? htmlspecialchars($material['txt_url']) : '' ?>">
            <?php elseif ($category === 'audio'): ?>
                <label for="audio_url">URL для прослушивания:</label>
                <input type="text" name="audio_url" id="audio_url" value="<?= $material ? htmlspecialchars($material['audio_url']) : '' ?>">
                
                <label for="audio_url2">URL для скачивания:</label>
                <input type="text" name="audio_url2" id="audio_url2" value="<?= $material ? htmlspecialchars($material['audio_url2']) : '' ?>">
            <?php endif; ?>
            
            <button type="submit">Сохранить</button>
            <a href="admin_manage.php?category=<?= $category ?>&group=<?= $group ?>" class="cancel-button">Отмена</a>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>