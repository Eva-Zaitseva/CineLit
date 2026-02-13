<?php
include './components/core.php';

$classFilter = isset($_GET['class']) ? $conn->real_escape_string($_GET['class']) : '';
$searchQuery = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$tables = [
    'primary' => [
        'table' => 'primary_audiobooks',
        'view_page' => 'view_audiobooks.php'
    ],
    'middle' => [
        'table' => 'middle_audiobooks',
        'view_page' => 'view_audiobooks.php'
    ],
    'senior' => [
        'table' => 'senior_audiobooks',
        'view_page' => 'view_audiobooks.php'
    ]
];

$hasAudiobooks = false;

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
        $hasAudiobooks = true;
        while ($audiobook = $result->fetch_assoc()) {
            echo '<a href="' . $data['view_page'] . '?id=' . $audiobook['id'] . '&from=' . $type . '" class="movie" title="' . htmlspecialchars($audiobook['title']) . '">';
            echo '<div class="movie-card-wrapper">';
            echo '<img src="image/' . htmlspecialchars($audiobook['image_url']) . '" alt="' . htmlspecialchars($audiobook['title']) . '">';
            
            $classLabel = '';
            if ($type == 'primary') {
                $classLabel = 'Нач. школа (' . $audiobook['class'] . ' кл.)';
            } elseif ($type == 'middle') {
                $classLabel = 'Ср. школа (' . $audiobook['class'] . ' кл.)';
            } else {
                $classLabel = 'Ст. школа (' . $audiobook['class'] . ' кл.)';
            }
            
            echo '<span class="class-label">' . $classLabel . '</span>';
            echo '<div class="movie-title-container">';
            echo '<span class="movie-title">' . htmlspecialchars($audiobook['title']) . '</span>';
            echo '<span class="movie-title-full">' . htmlspecialchars($audiobook['title']) . '</span>';
            echo '</div></div></a>';
        }
    }
}

if (!$hasAudiobooks) {
    echo '<div class="no-results">По вашему запросу ничего не найдено</div>';
}
?>