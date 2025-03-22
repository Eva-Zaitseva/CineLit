<?php
include "./components/core.php";
include "./components/headerAdmin.php";


if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
    header("Location: admin_auto.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];
    $video_url = $_POST['video_url'];
    $class = $_POST['class'];

    // Вставка данных в базу
    $stmt = $conn->prepare("INSERT INTO senior_movies (title, description, image_url, video_url, class) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $image_url, $video_url, $class);

    if ($stmt->execute()) {
        echo "Фильм добавлен успешно!";
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close();
}
?>

<main>
    <h1>Добавить фильм</h1>
    <form action="" method="POST">
        <label for="title">Название фильма:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Описание:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="image_url">URL изображения:</label>
        <input type="text" id="image_url" name="image_url" required>

        <label for="video_url">URL видео:</label>
        <input type="text" id="video_url" name="video_url" required>

        <label for="class">Класс</label>
        <input type="text" id="class" name="class" required>

        <button type="submit">Добавить фильм</button>
    </form>
</main>

<?php include 'footer.php'; ?>
