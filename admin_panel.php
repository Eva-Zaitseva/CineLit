<?php 
include "./components/core.php";
include "./components/headerAdmin.php";


if (isset($_SESSION['success'])) {
    echo "<p style='color: green;'>" . $_SESSION['success'] . "</p>";
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}

 ?>

<main>
<div class="editor">
    <a href="add_movie.php">Добавить фильм</a>
    <br>
    <a href="add_audiobook.php">Добавить аудиокнигу</a>
    <br>
    <a href="delete_movie.php">Удалить фильм</a>
    <br>
    <a href="update_movie.php">Редактировать фильм</a>
    <br>
    <a href="logout.php">Выйти</a>


   
</div>
</main>