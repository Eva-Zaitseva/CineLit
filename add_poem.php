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
    $audio_url = $_POST['audio_url'];
    $audio_url2 = $_POST['audio_url2'];
    $image_url = $_POST['image_url'];
    $class = $_POST['class'];

    // Вставка данных в базу
    $stmt = $conn->prepare("INSERT INTO senior_audiobooks (title, description, audio_url, audio_url2,image_url, class) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $description, $audio_url, $audio_url2, $image_url, $class);

    if ($stmt->execute()) {
        echo "Аудиокнига добавлена успешно!";
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close();
}
?>

<main>
    <h1>Добавить сборник</h1>
    <form action="" method="POST">
        <label for="title">Название аудиокниги:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Описание:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="audio_url">URL аудио:</label>
        <input type="text" id="audio_url" name="audio_url" required>

        <label for="audio_url2">URL аудио:</label>
        <input type="text" id="audio_url2" name="audio_url2" required>

        <label for="image_url">URL изображения:</label>
        <input type="text" id="image_url" name="image_url" required>

        

        <label for="class">Класс</label>
        <input type="text" id="class" name="class" required>

        <button type="submit">Добавить аудиокниги</button>
    </form>
</main>

<?php include 'footer.php'; ?>
