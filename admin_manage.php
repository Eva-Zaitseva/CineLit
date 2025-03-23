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

// Фильтрация по классам
$classFilter = isset($_GET['class']) ? $_GET['class'] : '';

// Удаление материала
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM $table WHERE id = $id";
    if ($conn->query($sql)) {
        $_SESSION['message'] = "Материал успешно удален";
    } else {
        $_SESSION['error'] = "Ошибка при удалении материала";
    }
    header("Location: admin_manage.php?category=$category&group=$group&class=$classFilter");
    exit();
}

// Получение списка материалов с учетом фильтрации по классам
$sql = "SELECT * FROM $table";
if (!empty($classFilter)) {
    $sql .= " WHERE class = '$classFilter'";
}
$result = $conn->query($sql);
?>

<main>
    <div class="admin-panel">
        <!-- Кнопка возврата к админ-панели -->
        <div style="margin-bottom: 20px;">
            <a href="admin_panel.php" class="back-button">Вернуться к админ-панели</a>
        </div>

        <h1>Управление материалами: <?= ucfirst($category) ?> (<?= ucfirst($group) ?>)</h1>
        
        <!-- Переключение между группами классов -->
        <div class="group-switcher">
            <a href="admin_manage.php?category=<?= $category ?>&group=primary" class="<?= $group === 'primary' ? 'active' : '' ?>">Начальные классы</a>
            <a href="admin_manage.php?category=<?= $category ?>&group=middle" class="<?= $group === 'middle' ? 'active' : '' ?>">Средние классы</a>
            <a href="admin_manage.php?category=<?= $category ?>&group=senior" class="<?= $group === 'senior' ? 'active' : '' ?>">Старшие классы</a>
        </div>

        <!-- Фильтрация по классам -->
        <div class="class-filter">
            <form action="admin_manage.php" method="GET" class="class_buttons">
                <input type="hidden" name="category" value="<?= $category ?>">
                <input type="hidden" name="group" value="<?= $group ?>">
                <?php if ($group === 'primary'): ?>
                    <!-- Кнопки для начальных классов -->
                    <button type="submit" name="class" value="1" class="class_btn <?= $classFilter == '1' ? 'active' : '' ?>">1 класс</button>
                    <button type="submit" name="class" value="2" class="class_btn <?= $classFilter == '2' ? 'active' : '' ?>">2 класс</button>
                    <button type="submit" name="class" value="3" class="class_btn <?= $classFilter == '3' ? 'active' : '' ?>">3 класс</button>
                    <button type="submit" name="class" value="4" class="class_btn <?= $classFilter == '4' ? 'active' : '' ?>">4 класс</button>
                <?php elseif ($group === 'middle'): ?>
                    <!-- Кнопки для средних классов -->
                    <button type="submit" name="class" value="5" class="class_btn <?= $classFilter == '5' ? 'active' : '' ?>">5 класс</button>
                    <button type="submit" name="class" value="6" class="class_btn <?= $classFilter == '6' ? 'active' : '' ?>">6 класс</button>
                    <button type="submit" name="class" value="7" class="class_btn <?= $classFilter == '7' ? 'active' : '' ?>">7 класс</button>
                    <button type="submit" name="class" value="8" class="class_btn <?= $classFilter == '8' ? 'active' : '' ?>">8 класс</button>
                    <button type="submit" name="class" value="9" class="class_btn <?= $classFilter == '9' ? 'active' : '' ?>">9 класс</button>
                <?php elseif ($group === 'senior'): ?>
                    <!-- Кнопки для старших классов -->
                    <button type="submit" name="class" value="10" class="class_btn <?= $classFilter == '10' ? 'active' : '' ?>">10 класс</button>
                    <button type="submit" name="class" value="11" class="class_btn <?= $classFilter == '11' ? 'active' : '' ?>">11 класс</button>
                <?php endif; ?>
                <button type="submit" name="class" value="" class="class_btn <?= empty($classFilter) ? 'active' : '' ?>">Все классы</button>
            </form>
        </div>

        <a href="admin_edit.php?category=<?= $category ?>&group=<?= $group ?>" class="add-button">Добавить новый материал</a>
        
        <?php if (isset($_SESSION['message'])): ?>
            <p style="color:green;"><?= $_SESSION['message'] ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p style="color:red;"><?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Класс</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['class']) ?></td>
                        <td>
                            <a href="admin_edit.php?category=<?= $category ?>&group=<?= $group ?>&id=<?= $row['id'] ?>">Редактировать</a>
                            <a href="admin_manage.php?category=<?= $category ?>&group=<?= $group ?>&class=<?= $classFilter ?>&delete=<?= $row['id'] ?>" onclick="return confirm('Вы уверены?')">Удалить</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include 'footer.php'; ?>