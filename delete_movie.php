<?php
include "./components/core.php"; // Подключаем базу данных
include "./components/headerAdmin.php"; // Подключаем заголовок


// Обработка запроса на удаление
if (isset($_GET['id']) && isset($_GET['type'])) {
    $contentId = intval($_GET['id']);
    $contentType = $_GET['type'];

    // Подготовленный запрос для удаления
    if ($contentType === 'senior_movies') {
        $stmt = $conn->prepare("DELETE FROM `senior_movies` WHERE `id` = ?");
    } elseif ($contentType === 'primary_movies') {
        $stmt = $conn->prepare("DELETE FROM `primary_movies` WHERE `id` = ?");
    }

    if (isset($stmt)) {
        $stmt->bind_param("i", $contentId);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Контент успешно удален!";
        } else {
            $_SESSION['error'] = "Ошибка при удалении контента.";
        }
        $stmt->close();
    }
}

// Получение списка фильмов и других категорий для отображения
$senior_movies = $conn->query("SELECT 'senior_movies' AS type, id, title FROM `senior_movies`");
$primary_movies = $conn->query("SELECT 'primary_movies' AS type, id, title FROM `primary_movies`");
?>

<main>
    <h2>Удаление контента</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Тип</th>
            <th>Действие</th>
        </tr>

        

        <!-- Фильмы для пожилых -->
        <tr>
            <td colspan="4"><strong>Фильмы для старших классов</strong></td>
        </tr>
        <?php while($content = $senior_movies->fetch_assoc()): ?>
            <tr>
                <td><?php echo $content['id']; ?></td>
                <td><?php echo $content['title']; ?></td>
                <td><?php echo ucfirst($content['type']); ?></td>
                <td>
                    <a href="delete_movie.php?id=<?php echo $content['id']; ?>&type=<?php echo $content['type']; ?>" onclick="return confirm('Вы уверены, что хотите удалить этот контент?');">Удалить</a>
                </td>
            </tr>
        <?php endwhile; ?>

        <tr>
            <td colspan="4" style="border-top: 2px solid #ccc;"></td>
        </tr>

        <!-- Документальные фильмы -->
        <tr>
            <td colspan="4"><strong>Фильмы для младших классов</strong></td>
        </tr>
        <?php while($content = $primary_movies->fetch_assoc()): ?>
            <tr>
                <td><?php echo $content['id']; ?></td>
                <td><?php echo $content['title']; ?></td>
                <td><?php echo ucfirst($content['type']); ?></td>
                <td>
                    <a href="delete_movie.php?id=<?php echo $content['id']; ?>&type=<?php echo $content['type']; ?>" onclick="return confirm('Вы уверены, что хотите удалить этот контент?');">Удалить</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </table>
</main>

</body>
</html>
