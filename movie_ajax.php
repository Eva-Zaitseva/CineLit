<?php
include './components/core.php';

$classFilter = isset($_GET['class']) ? $conn->real_escape_string($_GET['class']) : '';
$searchQuery = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$tables = [
    'primary' => [
        'table' => 'primary_movies',
        'view_page' => 'view_movie.php'
    ],
    'middle' => [
        'table' => 'middle_movies',
        'view_page' => 'view_movie.php'
    ],
    'senior' => [
        'table' => 'senior_movies',
        'view_page' => 'view_movie.php'
    ]
];

$hasMovies = false;

// Формируем SQL запрос с учетом фильтров
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
        $hasMovies = true;
        while ($movie = $result->fetch_assoc()) {
            echo '<a href="' . $data['view_page'] . '?id=' . $movie['id'] . '&from=' . $type . '" class="movie" title="' . htmlspecialchars($movie['title']) . '">';
            $imagePath = 'image/' . htmlspecialchars($movie['image_url']);
            
            $classLabel = '';
            if ($type == 'primary') {
                $classLabel = 'Нач. школа (' . $movie['class'] . ' кл.)';
            } elseif ($type == 'middle') {
                $classLabel = 'Ср. школа (' . $movie['class'] . ' кл.)';
            } else {
                $classLabel = 'Ст. школа (' . $movie['class'] . ' кл.)';
            }
            
            echo '<div class="movie-card-wrapper">';
            echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($movie['title']) . '">';
            echo '<span class="class-label">' . $classLabel . '</span>';
            echo '<div class="movie-title-container">';
            echo '<span class="movie-title">' . htmlspecialchars($movie['title']) . '</span>';
            echo '<span class="movie-title-full">' . htmlspecialchars($movie['title']) . '</span>';
            echo '</div>';
            echo '</div>';
            echo '</a>';
        }
    }
}

if (!$hasMovies) {
    echo '<div class="no-results">По вашему запросу ничего не найдено</div>';
}
?>