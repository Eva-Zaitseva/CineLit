<?php
include './components/core.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$type = isset($_GET['type']) ? $_GET['type'] : '';
$classFilter = isset($_GET['class']) ? $conn->real_escape_string($_GET['class']) : '';
$searchQuery = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$itemsPerPage = 12;
$offset = ($page - 1) * $itemsPerPage;

$tables = [
    'movies' => [
        'table' => 'senior_movies',
        'view_page' => 'senior_view.php'
    ],
    'performances' => [
        'table' => 'senior_performances',
        'view_page' => 'senior_view_performance.php'
    ],
    'poems' => [
        'table' => 'senior_poems',
        'view_page' => 'senior_view_poem.php'
    ],
    'audio' => [
        'table' => 'senior_audiobooks',
        'view_page' => 'senior_view_audiobooks.php'
    ]
];

// Получаем данные для запрошенного типа
if (isset($tables[$type])) {
    $tableData = $tables[$type];
    $table = $tableData['table'];
    
    // Формируем SQL запрос
    $where = [];
    if (!empty($searchQuery)) {
        $where[] = "title LIKE '%$searchQuery%'";
    }
    if (!empty($classFilter)) {
        $where[] = "class = '$classFilter'";
    }
    
    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    
    // Запрос для контента
    $sql = "SELECT * FROM $table $whereClause LIMIT $itemsPerPage OFFSET $offset";
    $result = $conn->query($sql);
    
    // Генерируем HTML контента
    $content = '';
    while ($item = $result->fetch_assoc()) {
        $imagePath = 'image/' . htmlspecialchars($item['image_url']);
        
        $content .= '<a href="' . $tableData['view_page'] . '?id=' . $item['id'] . '" class="movie" title="' . htmlspecialchars($item['title']) . '">';
        $content .= '<div class="movie-card-wrapper">';
        $content .= '<img src="' . $imagePath . '" alt="' . htmlspecialchars($item['title']) . '">';
        $content .= '<span class="class-label">' . $item['class'] . ' класс</span>';
        $content .= '<div class="movie-title-container">';
        $content .= '<span class="movie-title">' . htmlspecialchars($item['title']) . '</span>';
        $content .= '<span class="movie-title-full">' . htmlspecialchars($item['title']) . '</span>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</a>';
    }
    
    // Запрос для общего количества элементов
    $countSql = "SELECT COUNT(*) as total FROM $table $whereClause";
    $totalItems = $conn->query($countSql)->fetch_assoc()['total'];
    $totalPages = ceil($totalItems / $itemsPerPage);
    
    // Генерируем HTML пагинации
    $pagination = '';
    if ($totalPages > 1) {
        $pagination .= '<div class="pagination">';
        for ($i = 1; $i <= $totalPages; $i++) {
            $activeClass = ($i == $page) ? 'active' : '';
            $pagination .= '<button class="page-link ' . $activeClass . '" 
                            data-page="' . $i . '" 
                            data-type="' . $type . '"
                            data-class="' . $classFilter . '"
                            data-search="' . $searchQuery . '">' . $i . '</button>';
        }
        $pagination .= '</div>';
    }
    
    // Возвращаем JSON ответ
    header('Content-Type: application/json');
    echo json_encode([
        'content' => $content,
        'pagination' => $pagination
    ]);
    exit;
}

// Если тип не распознан
header('HTTP/1.1 400 Bad Request');
echo json_encode(['error' => 'Invalid request']);
exit;
?>