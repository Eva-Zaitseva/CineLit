<?php
include './components/core.php';

$classFilter = isset($_GET['class']) ? $conn->real_escape_string($_GET['class']) : '';
$searchQuery = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$tables = [
    'primary' => [
        'table' => 'primary_poems',
        'view_page' => 'view_poem.php'
    ],
    'middle' => [
        'table' => 'middle_poems',
        'view_page' => 'view_poem.php'
    ],
    'senior' => [
        'table' => 'senior_poems',
        'view_page' => 'view_poem.php'
    ]
];

$hasPoems = false;

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
        $hasPoems = true;
        while ($poem = $result->fetch_assoc()) {
            echo '<a href="' . $data['view_page'] . '?id=' . $poem['id'] . '&from=' . $type . '" class="movie" title="' . htmlspecialchars($poem['title']) . '">';
            echo '<div class="movie-card-wrapper">';
            echo '<img src="image/' . htmlspecialchars($poem['image_url']) . '" alt="' . htmlspecialchars($poem['title']) . '">';
            
            $classLabel = '';
            if ($type == 'primary') {
                $classLabel = 'Нач. школа (' . $poem['class'] . ' кл.)';
            } elseif ($type == 'middle') {
                $classLabel = 'Ср. школа (' . $poem['class'] . ' кл.)';
            } else {
                $classLabel = 'Ст. школа (' . $poem['class'] . ' кл.)';
            }
            
            echo '<span class="class-label">' . $classLabel . '</span>';
            echo '<div class="movie-title-container">';
            echo '<span class="movie-title">' . htmlspecialchars($poem['title']) . '</span>';
            echo '<span class="movie-title-full">' . htmlspecialchars($poem['title']) . '</span>';
            echo '</div></div></a>';
        }
    }
}

if (!$hasPoems) {
    echo '<div class="no-results">По вашему запросу ничего не найдено</div>';
}
?>