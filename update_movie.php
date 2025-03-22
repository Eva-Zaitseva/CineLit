<?php
include "./components/core.php";
include "./components/headerAdmin.php"; // Подключение заголовка
 // Подключение к базе данных

// Проверяем, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['movie_id'];
    $table = $_POST['table']; // Получаем название таблицы из формы

    // Получаем данные о фильме
    $stmt = $conn->prepare("SELECT * FROM `$table` WHERE `id` = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $movie = $result->fetch_assoc();

    if (!$movie) {
        $_SESSION['error'] = "Фильм не найден.";
    } else {
        // Обновление данных о фильме
        if (isset($_POST['updateMovie'])) {
            $newTitle = $_POST['titleUpdate'];
            $newDescription = $_POST['descriptionUpdate'];
            $newVideoUrl = $_POST['videoUrlUpdate'];
            $newImageUrl = $_POST['imageUrlUpdate'];

            // Обновляем данные в базе
            $stmt = $conn->prepare("UPDATE `$table` SET `title` = ?, `description` = ?, `video_url` = ?, `image_url` = ? WHERE `id` = ?");
            $stmt->bind_param("ssssi", $newTitle, $newDescription, $newVideoUrl, $newImageUrl, $id);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Данные о фильме успешно обновлены!";
                header("Location: admin_panel.php"); // Перенаправляем на страницу управления фильмами
                exit();
            } else {
                $_SESSION['error'] = "Ошибка при обновлении данных о фильме.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="../../product/style_admin.css"> -->
    <title>Обновление фильма</title>
</head>
<body>
    <h1 style="text-align: center; padding: 50px 0 10px 0">Обновление фильма</h1>

    <form action="" method="POST" style="text-align: center; margin: 20px;">
        <label for="table">Выберите таблицу:</label>
        <select name="table" id="table" required>
            <option value="movies">movies</option>
            <option value="primary_movies">primary_movies</option>
            <option value="senior_movies">senior_movies</option>
            <option value="senior_performances">senior_performances</option>
        </select>

        <label for="movie_id">ID фильма:</label>
        <input type="number" name="movie_id" id="movie_id" required>

        <button type="submit">Найти фильм</button>
    </form>

    <?php if (isset($movie)): ?>
        <div class="updateForm" style="text-align: center;">
            <form action="" method="POST">
                <input type="hidden" name="table" value="<?php echo htmlspecialchars($table); ?>">
                <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movie['id']); ?>">

                <label for="titleUpdate">Новое название:</label>
                <input type="text" name="titleUpdate" id="titleUpdate" value="<?php echo htmlspecialchars($movie['title']); ?>" required>

                <label for="descriptionUpdate">Новое описание:</label>
                <textarea name="descriptionUpdate" id="descriptionUpdate" required><?php echo htmlspecialchars($movie['description']); ?></textarea>

                <label for="videoUrlUpdate">Новое видео URL:</label>
                <input type="text" name="videoUrlUpdate" id="videoUrlUpdate" value="<?php echo htmlspecialchars($movie['video_url']); ?>">

                <label for="imageUrlUpdate">Новое изображение URL:</label>
                <input type="text" name="imageUrlUpdate" id="imageUrlUpdate" value="<?php echo htmlspecialchars($movie['image_url']); ?>">

                <button type="submit" name="updateMovie">Обновить фильм</button>
            </form>
        </div>
    <?php endif; ?>

