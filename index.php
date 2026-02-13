<?php
include "./components/header.php";
?>

<main>
    <div class="container">
<div class="glav">
    <div class="text-overlay">
        <h1>Добро пожаловать в "CineLit"</h1>
        <h2>Ваше пространство для погружения в кино и литературу</h2>
    </div>

    <div class="class">
        <h1>ВЫБЕРИ СВОЙ КЛАСС,<br>ЧТОБЫ ПОСМОТРЕТЬ ФИЛЬМ, СПЕКТАКЛЬ, ПРОЧИТАТЬ СТИХИ ИЛИ ПОСЛУШАТЬ АУДИОКНИГУ</h1>

        <div class="btn">
            <button class="button" onclick="window.location.href='primary.php'">
                НАЧАЛЬНЫЙ
                <span>(1 - 4)</span>
            </button>
            <button class="button" onclick="window.location.href='middle.php'">
                СРЕДНИЙ
                <span>(5 - 9)</span>
            </button>
            <button class="button" onclick="window.location.href='senior.php'">
                СТАРШИЙ
                <span>(10 - 11)</span>
            </button>
        </div>
    </div>
</div>

        <div class="inform">
            <div class="text">
                <div class="cel">
                    <p>Наша цель — <b>сделать обучение увлекательным и доступным</b> для каждого ученика. Мы собрали
                        уникальные материалы, которые помогут вам открыть для себя мир литературы, искусства и знаний.
                    </p>
                </div>
                <div class="razd">
                    <p>На нашем сайте вы найдете:</p>
                    <ul>
                        <li><b>ФИЛЬМЫ</b>, созданные по мотивам школьной программы и классических произведений</li>
                        <li><b>СПЕКТАКЛИ</b>, которые оживляют страницы книг и знакомят с миром театра</li>
                        <li><b>СТИХИ</b> великих поэтов, которые вдохновляют и помогают лучше понять литературу</li>
                        <li><b>АУДИОКНИГИ</b>, которые сделают изучение литературы удобным и интересным</li>
                    </ul>
                </div>
                <div class="miverim">
                    <p>Мы верим, что книги, фильмы и спектакли — это не только источник знаний, но и способ развить
                        воображение, научиться сопереживать и лучше понимать мир вокруг нас.</p>
                </div>
                <div class="cineliteto">
                    <p>Школьная библиотека — это место, где каждый ученик может найти что-то интересное для себя, будь
                        то подготовка к уроку, поиск вдохновения или просто увлекательное чтение.</p>
                </div>
            </div>

            <div class="image">
                <div class="img1">
                    <img src="image/txt1.jpg" alt="Стопка книг и проектор">
                </div>
                <div class="img2">
                    <img src="image/txt2.jpg" alt="Письмо с пером">
                </div>
                <div class="img3">
                    <img src="image/txt3.jpg" alt="Письмо с пером">
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php';?>