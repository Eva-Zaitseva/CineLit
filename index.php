<?php
include "./components/header.php";

?>

    

    <main>
        <div class="container">

            <div class="glav">
            <div class="image-block">
    <img src="image/glav.jpg" alt="Кинопленка" width="100%" height="820px">
    <div class="text-overlay">
        <h1>Добро пожаловать в "CineLit"</h1>
        <h2>Ваше пространство для погружения в кино и литературу</h2>
    </div>
    
    <div class="class">
    <h1>ВЫБЕРИ СВОЙ КЛАСС, <br> ЧТОБЫ ПОСМОТРЕТЬ ФИЛЬМ, СПЕКТАКЛЬ, ПРОЧИТАТЬ СТИХИ ИЛИ ПОСЛУШАТЬ АУДИОКНИГУ</h1>

<div class="btn">

<!-- <button class="button" onclick="showSection('начальный')">Начальный(1-4)</button> -->

<button class="button" onclick="window.location.href='primary.php'">Начальный<br>(1 - 4)</button>
    <button class="button" onclick="window.location.href='middle.php'">Средний<br>(5 - 9)</button>
    <button class="button" onclick="window.location.href='senior.php'">Старший<br>(10 - 11)</button>
</div>



    <!-- <div id="hidden-section" class="hidden-section">
        <p>Вы выбрали: <span id="class-name"></span></p>
    </div> -->
</div>

</div>
            </div>



<!-- <script>
    function showSection(classType) {
        document.getElementById('hidden-section').style.display = 'block'; // Показываем скрытый раздел
        document.getElementById('class-name').innerText = classType; // Устанавливаем текст в скрытом разделе
    }
</script> -->


<div class="inform">
                <div class="text">
                    <div class="cel">
                        <p>Цель — <b>объединить любителей книг и фильмов</b>, наш сайт предоставляет уникальный контент, который вдохновляет и развлекает.</p>
                    </div>
                    <div class="razd">
                        <p>У нас вы найдете:</p>
                        <ul>
                            <li><b>ФИЛЬМЫ</b> по произведениям классиков и современных авторов</li>
                            <li><b>СПЕКТАКЛИ</b>, которые вдохновляют и погружают в мир искусства</li>
                            <li><b>СТИХОТВОРЕНИЯ</b> выдающихся поэтов, которые трогают сердце и вдохновляют</li>
                        </ul>
                    </div>
                    <div class="miverim">
                        <p>Мы верим, что литература и кино — это два мощных инструмента, которые могут изменить наше восприятие мира.</p>
                    </div>

        <div class="cineliteto">
            <p>CineLit — ваше идеальное место для вдохновения, независимо от того, являетесь ли вы заядлым читателем, киноманом или просто ищете что-то новое.</p>
        </div>

    </div>


    <div class="image">

        <div class="img1">
            <img src="image/txt1.jpg" alt="Стопка книг и проектор">
        </div>

        <div class="img2">
            <img src="image/txt2.jpg" alt="Письмо с пером">
        </div>

    </div>


</div>



        </div>
    </main>
    
<?php include 'footer.php';?>