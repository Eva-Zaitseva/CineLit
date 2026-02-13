<footer>
    <div class="container">
        <div class="navigacia">
            <h2>НАВИГАЦИЯ</h2>
            <ul>
                <li><a href="movie.php">ФИЛЬМЫ</a></li>
                <li><a href="spect.php">СПЕКТАКЛИ</a></li>
                <li><a href="poetes.php">ПОЭЗИЯ</a></li>
                <li><a href="audiobooks.php">АУДИОКНИГИ</a></li>
            </ul>
        </div>

        <div class="direktor">
            <h2>ДИРЕКТОР</h2>
            <a href="tel:+73812904593">8 (3812) 90-45-93</a>
        </div>

        <div class="buhgalteria">
            <h2>БУХГАЛТЕРИЯ</h2>
            <a href="tel:+73812904592">8 (3812) 90-45-92</a>
        </div>

        <div class="email">
            <h2>E-MAIL</h2>
            <a href="mailto:SCHOOL81@BOU.OMSKPORTAL.RU">school81@bou.omskportal.ru</a>
        </div>

        <div class="polkonf">
            <!-- <a href="#"><h2>ПОЛИТИКА <br> КОНФИДЕНЦИАЛЬНОСТИ</h2></a> -->
            <?php
                if (!isset($_SESSION['user'])) {
                    echo "<a href='admin_auto.php' style='text-decoration:none; color:#F8F8F8; font-family:Arial; font-size:14px;'>Администратор</a>";
                } elseif ($_SESSION['user']['type'] === 'admin') {
                    echo "<a href='logout.php' style='text-decoration:none; color:#F8F8F8; font-family:Arial; font-size:14px;'>Выход</a> <br>";
                    echo "<a href='admin_panel.php' style='text-decoration:none; color:#F8F8F8; font-family:Arial; font-size:14px;'>Админ-панель</a>";
                }
            ?>
        </div>

        <div class="vk">
            <a href="https://vk.com/doo_poisk">
                <img src="image/vk.jpg" alt="VK" style="border-radius: 100%;">
            </a>
        </div>
    </div>
    <div class="copyright">
        ©Зайцева Е.П. 2025
    </div>
</footer>
</body>
</html>