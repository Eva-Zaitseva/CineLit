<?php
include './components/core.php';

$classFilter = isset($_GET['class']) ? $conn->real_escape_string($_GET['class']) : '';
$searchQuery = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$tables = [
    'primary' => [
        'table' => 'primary_performances',
        'view_page' => 'view_performance.php'
    ],
    'middle' => [
        'table' => 'middle_performances',
        'view_page' => 'view_performance.php'
    ],
    'senior' => [
        'table' => 'senior_performances',
        'view_page' => 'view_performance.php'
    ]
];

$hasPerformances = false;

foreach ($tables as $type => $data) {
    $table = $data['table'];
    $where = [];
    
    if (!empty($searchQuery)) {
        $where[] = "title LIKE '%$searchQuery%'";
    }
    if (!empty($classFilter)) {
        $where[] = "class = '$classFilter'";
    }
    
    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    $sql = "SELECT * FROM $table $whereClause";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $hasPerformances = true;
        while ($performance = $result->fetch_assoc()) {
            echo '<a href="' . $data['view_page'] . '?id=' . $performance['id'] . '&from=' . $type . '" class="movie" title="' . htmlspecialchars($performance['title']) . '">';
            echo '<div class="movie-card-wrapper">';
            echo '<img src="image/' . htmlspecialchars($performance['image_url']) . '" alt="' . htmlspecialchars($performance['title']) . '">';
            
            $classLabel = '';
            if ($type == 'primary') {
                $classLabel = 'Нач. школа (' . $performance['class'] . ' кл.)';
            } elseif ($type == 'middle') {
                $classLabel = 'Ср. школа (' . $performance['class'] . ' кл.)';
            } else {
                $classLabel = 'Ст. школа (' . $performance['class'] . ' кл.)';
            }
            
            echo '<span class="class-label">' . $classLabel . '</span>';
            echo '<div class="movie-title-container">';
            echo '<span class="movie-title">' . htmlspecialchars($performance['title']) . '</span>';
            echo '<span class="movie-title-full">' . htmlspecialchars($performance['title']) . '</span>';
            echo '</div></div></a>';
        }
    }
}

if (!$hasPerformances) {
    echo '<div class="no-results">По вашему запросу ничего не найдено</div>';
}
?>