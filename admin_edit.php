<?php
include "components/core.php";
include "components/header.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
    header("Location: admin_auto.php");
    exit();
}

$category = isset($_GET['category']) ? $_GET['category'] : '';
$group = isset($_GET['group']) ? $_GET['group'] : 'primary';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

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
    $description = $conn->real_escape_string($_POST['description']);

    // Обработка загрузки изображения
    $image_url = $material['image_url'] ?? '';
    
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'image/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedType = finfo_file($fileInfo, $_FILES['image_file']['tmp_name']);
        
        if (in_array($detectedType, $allowedTypes)) {
            // Генерируем уникальное имя файла
            $extension = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('img_') . '.' . $extension;
            $destination = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $destination)) {
                $image_url = $fileName;
                
                // Удаляем старое изображение, если оно существует и это редактирование
                if ($id > 0 && !empty($material['image_url']) && file_exists($uploadDir . $material['image_url'])) {
                    unlink($uploadDir . $material['image_url']);
                }
            } else {
                $_SESSION['error'] = "Ошибка при загрузке изображения";
            }
        } else {
            $_SESSION['error'] = "Недопустимый тип файла. Разрешены только JPEG, JPG, PNG и GIF.";
        }
    }

    // Дополнительные поля в зависимости от категории
    if ($category === 'movies' || $category === 'performances') {
        $video_url = $conn->real_escape_string($_POST['video_url']);
    } elseif ($category === 'poems') {
        // Обработка файлов для стихов
        $fileFields = ['pdf_url', 'epub_url', 'fb2_url', 'txt_url'];
        foreach ($fileFields as $field) {
            $$field = $material[$field] ?? '';
            
            if (isset($_FILES[$field . '_file']) && $_FILES[$field . '_file']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/poems/';
                $allowedTypes = [
                    'pdf_url' => ['application/pdf'],
                    'epub_url' => ['application/epub+zip'],
                    'fb2_url' => ['text/xml', 'application/xml'],
                    'txt_url' => ['text/plain']
                ];
                
                $detectedType = finfo_file($fileInfo, $_FILES[$field . '_file']['tmp_name']);
                
                if (in_array($detectedType, $allowedTypes[$field])) {
                    $extension = pathinfo($_FILES[$field . '_file']['name'], PATHINFO_EXTENSION);
                    $fileName = uniqid($field . '_') . '.' . $extension;
                    $destination = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES[$field . '_file']['tmp_name'], $destination)) {
                        $$field = $fileName;
                        
                        // Удаляем старый файл, если он существует
                        if ($id > 0 && !empty($material[$field]) && file_exists($uploadDir . $material[$field])) {
                            unlink($uploadDir . $material[$field]);
                        }
                    } else {
                        $_SESSION['error'] = "Ошибка при загрузке файла " . $field;
                    }
                } else {
                    $_SESSION['error'] = "Недопустимый тип файла для " . $field;
                }
            }
        }
    } elseif ($category === 'audio') {
        // Обработка файлов для аудиокниг
        $fileFields = ['audio_url', 'audio_url2'];
        foreach ($fileFields as $field) {
            $$field = $material[$field] ?? '';
            
            if (isset($_FILES[$field . '_file']) && $_FILES[$field . '_file']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/audio/';
                $allowedTypes = ['audio/mpeg', 'audio/mp3', 'audio/mp4', 'audio/x-m4a'];
                
                $detectedType = finfo_file($fileInfo, $_FILES[$field . '_file']['tmp_name']);
                
                if (in_array($detectedType, $allowedTypes)) {
                    $extension = pathinfo($_FILES[$field . '_file']['name'], PATHINFO_EXTENSION);
                    $fileName = uniqid($field . '_') . '.' . $extension;
                    $destination = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES[$field . '_file']['tmp_name'], $destination)) {
                        $$field = $fileName;
                        
                        // Удаляем старый файл, если он существует
                        if ($id > 0 && !empty($material[$field]) && file_exists($uploadDir . $material[$field])) {
                            unlink($uploadDir . $material[$field]);
                        }
                    } else {
                        $_SESSION['error'] = "Ошибка при загрузке аудиофайла " . $field;
                    }
                } else {
                    $_SESSION['error'] = "Недопустимый тип аудиофайла. Разрешены MP3 и MP4.";
                }
            }
        }
    }

    if (!isset($_SESSION['error'])) {
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
}
?>

<link rel="stylesheet" href="styles/admin.css">
<style>
    .edit-header {
        margin-bottom: 20px;
    }
    .edit-header h1 {
        margin-bottom: 5px;
    }
    .edit-header .location-info {
        font-size: 16px;
        color: #555;
        margin-bottom: 15px;
    }
    .file-upload-container {
        margin-bottom: 15px;
    }
    .current-file {
        max-width: 100%;
        display: block;
        margin: 10px 0;
        font-size: 14px;
        color: #666;
    }
    .file-input-label {
        display: inline-block;
        padding: 8px 15px;
        background: rgb(67, 93, 67);
        color: rgb(255, 255, 255);
        border-radius: 4px;
        cursor: pointer;
        margin-top: 10px;
    }
    .file-input-label:hover {
        background: #2a412a;
        color: rgb(255, 255, 255);
    }
    .file-input {
        display: none;
    }
    .file-name {
        display: inline-block;
        margin-left: 10px;
        font-size: 14px;
    }
    .current-image {
        max-width: 200px;
        max-height: 200px;
        display: block;
        margin: 10px 0;
    }
</style>

<main>
    <div class="admin-panel-edit">
        <div class="edit-header">
            <h1><?= $id > 0 ? "Редактирование материала" : "Добавление нового материала" ?></h1>
            <div class="location-info">
                Категория: <strong><?= 
                    $category === 'movies' ? 'Фильмы' : 
                    ($category === 'performances' ? 'Спектакли' : 
                    ($category === 'poems' ? 'Поэзия' : 'Аудиокниги')) 
                ?></strong> | 
                Группа: <strong><?= 
                    $group === 'primary' ? 'Начальные классы' : 
                    ($group === 'middle' ? 'Средние классы' : 'Старшие классы')
                ?></strong>
            </div>
        </div>
        
        <?php if (isset($_SESSION['error'])): ?>
            <p style="color:red;"><?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label for="title">Название:</label>
            <input type="text" name="title" id="title" value="<?= $material ? htmlspecialchars($material['title']) : '' ?>" required>
            
            <label for="class">Класс:</label>
            <input type="text" name="class" id="class" value="<?= $material ? htmlspecialchars($material['class']) : '' ?>" required>
            
            <div class="file-upload-container">
                <label>Изображение:</label>
                <?php if ($id > 0 && !empty($material['image_url'])): ?>
                    <p>Текущее изображение:</p>
                    <img src="image/<?= htmlspecialchars($material['image_url']) ?>" class="current-image" alt="Текущее изображение">
                <?php endif; ?>
                
                <label for="image_file" class="file-input-label">Выбрать файл</label>
                <input type="file" name="image_file" id="image_file" class="file-input" accept="image/jpeg,image/jpg,image/png,image/gif">
                <span id="image_file_name" class="file-name">Файл не выбран</span>
                
                <input type="hidden" name="image_url" value="<?= $material ? htmlspecialchars($material['image_url']) : '' ?>">
            </div>
            
            <label for="description">Описание:</label>
            <textarea name="description" id="description" required><?= $material ? htmlspecialchars($material['description']) : '' ?></textarea>

            <!-- Дополнительные поля в зависимости от категории -->
            <?php if ($category === 'movies' || $category === 'performances'): ?>
                <label for="video_url">URL видео:</label>
                <input type="text" name="video_url" id="video_url" value="<?= $material ? htmlspecialchars($material['video_url']) : '' ?>" required>
            
            <?php elseif ($category === 'poems'): ?>
                <!-- PDF -->
                <div class="file-upload-container">
                    <label>PDF файл:</label>
                    <?php if ($id > 0 && !empty($material['pdf_url'])): ?>
                        <span class="current-file">Текущий файл: <?= htmlspecialchars($material['pdf_url']) ?></span>
                    <?php endif; ?>
                    
                    <label for="pdf_url_file" class="file-input-label">Выбрать PDF</label>
                    <input type="file" name="pdf_url_file" id="pdf_url_file" class="file-input" accept=".pdf,application/pdf">
                    <span id="pdf_url_file_name" class="file-name">Файл не выбран</span>
                    
                    <input type="hidden" name="pdf_url" value="<?= $material ? htmlspecialchars($material['pdf_url']) : '' ?>">
                </div>
                
                <!-- EPUB -->
                <div class="file-upload-container">
                    <label>EPUB файл:</label>
                    <?php if ($id > 0 && !empty($material['epub_url'])): ?>
                        <span class="current-file">Текущий файл: <?= htmlspecialchars($material['epub_url']) ?></span>
                    <?php endif; ?>
                    
                    <label for="epub_url_file" class="file-input-label">Выбрать EPUB</label>
                    <input type="file" name="epub_url_file" id="epub_url_file" class="file-input" accept=".epub,application/epub+zip">
                    <span id="epub_url_file_name" class="file-name">Файл не выбран</span>
                    
                    <input type="hidden" name="epub_url" value="<?= $material ? htmlspecialchars($material['epub_url']) : '' ?>">
                </div>
                
                <!-- FB2 -->
                <div class="file-upload-container">
                    <label>FB2 файл:</label>
                    <?php if ($id > 0 && !empty($material['fb2_url'])): ?>
                        <span class="current-file">Текущий файл: <?= htmlspecialchars($material['fb2_url']) ?></span>
                    <?php endif; ?>
                    
                    <label for="fb2_url_file" class="file-input-label">Выбрать FB2</label>
                    <input type="file" name="fb2_url_file" id="fb2_url_file" class="file-input" accept=".fb2,.fb2.zip,application/xml,text/xml">
                    <span id="fb2_url_file_name" class="file-name">Файл не выбран</span>
                    
                    <input type="hidden" name="fb2_url" value="<?= $material ? htmlspecialchars($material['fb2_url']) : '' ?>">
                </div>
                
                <!-- TXT -->
                <div class="file-upload-container">
                    <label>TXT файл:</label>
                    <?php if ($id > 0 && !empty($material['txt_url'])): ?>
                        <span class="current-file">Текущий файл: <?= htmlspecialchars($material['txt_url']) ?></span>
                    <?php endif; ?>
                    
                    <label for="txt_url_file" class="file-input-label">Выбрать TXT</label>
                    <input type="file" name="txt_url_file" id="txt_url_file" class="file-input" accept=".txt,text/plain">
                    <span id="txt_url_file_name" class="file-name">Файл не выбран</span>
                    
                    <input type="hidden" name="txt_url" value="<?= $material ? htmlspecialchars($material['txt_url']) : '' ?>">
                </div>
            
            <?php elseif ($category === 'audio'): ?>
                <!-- Аудио для прослушивания -->
                <div class="file-upload-container">
                    <label>Аудиофайл для прослушивания:</label>
                    <?php if ($id > 0 && !empty($material['audio_url'])): ?>
                        <span class="current-file">Текущий файл: <?= htmlspecialchars($material['audio_url']) ?></span>
                    <?php endif; ?>
                    
                    <label for="audio_url_file" class="file-input-label">Выбрать аудиофайл</label>
                    <input type="file" name="audio_url_file" id="audio_url_file" class="file-input" accept=".mp3,.mp4,.m4a,audio/mpeg,audio/mp4">
                    <span id="audio_url_file_name" class="file-name">Файл не выбран</span>
                    
                    <input type="hidden" name="audio_url" value="<?= $material ? htmlspecialchars($material['audio_url']) : '' ?>">
                </div>
                
                <!-- Аудио для скачивания -->
                <div class="file-upload-container">
                    <label>Аудиофайл для скачивания:</label>
                    <?php if ($id > 0 && !empty($material['audio_url2'])): ?>
                        <span class="current-file">Текущий файл: <?= htmlspecialchars($material['audio_url2']) ?></span>
                    <?php endif; ?>
                    
                    <label for="audio_url2_file" class="file-input-label">Выбрать аудиофайл</label>
                    <input type="file" name="audio_url2_file" id="audio_url2_file" class="file-input" accept=".mp3,.mp4,.m4a,audio/mpeg,audio/mp4">
                    <span id="audio_url2_file_name" class="file-name">Файл не выбран</span>
                    
                    <input type="hidden" name="audio_url2" value="<?= $material ? htmlspecialchars($material['audio_url2']) : '' ?>">
                </div>
            <?php endif; ?>
            
            <button type="submit">Сохранить</button>
            <a href="admin_manage.php?category=<?= $category ?>&group=<?= $group ?>" class="cancel-button">Отмена</a>
        </form>
    </div>
</main>

<script>
    // Функция для отображения имени выбранного файла
    function setupFileInput(inputId, displayId) {
        document.getElementById(inputId).addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Файл не выбран';
            document.getElementById(displayId).textContent = fileName;
        });
    }

    // Настройка всех полей выбора файлов
    setupFileInput('image_file', 'image_file_name');
    <?php if ($category === 'poems'): ?>
        setupFileInput('pdf_url_file', 'pdf_url_file_name');
        setupFileInput('epub_url_file', 'epub_url_file_name');
        setupFileInput('fb2_url_file', 'fb2_url_file_name');
        setupFileInput('txt_url_file', 'txt_url_file_name');
    <?php elseif ($category === 'audio'): ?>
        setupFileInput('audio_url_file', 'audio_url_file_name');
        setupFileInput('audio_url2_file', 'audio_url2_file_name');
    <?php endif; ?>
</script>

<?php include 'footer.php'; ?>