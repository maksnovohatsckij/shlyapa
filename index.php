<?php require "./session/scores.php" ?>
<!DOCTYPE html>
<html lang='ru'>

<head>
    <title>Шляпа</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta content='true' name='HandheldFriendly' />
    <meta content='width' name='MobileOptimized' />
    <meta content='yes' name='apple-mobile-web-app-capable' />
    <meta name="keywords" content="шляпа, игра шляпа, Приложение для игры в шляпу, shlyapa, shlyapa-online, онлайн шляпа, shlyapa-online-app" />
    <meta name="description" content="Веб-приложение для игры в шляпу" />
    <meta property="og:image" content="https://repository-images.githubusercontent.com/260586896/2b857080-8c27-11ea-947f-f783d94c89aa">
    <meta property="og:title" content="Шляпа">
    <meta property="og:description" content="Веб-приложение для игры в шляпу">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:type" content="website" />
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
    <link rel="stylesheet" href="./css/style.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="./js/script.js"></script>
</head>

<body>

    <header>
        <h1>Шляпа 👒</h1>
        <span><a style="transition: 0.1s;" href="https://github.com/maxchistt/shlyapa" target="_blank" class="text-secondary">GitHub</a></span>
    </header>

    <div id="menu">
        <div id="menuclosecontainer" class="container">
            <div id="current_room"></div>
            <button id="menuclose" class="btn shadow-none">Назад</button>
        </div>
        <div class="container mb-2">
            <div class="mb-1">Добавить слова:</div>
            <input placeholder="слово 1" class="newword form-control mb-1" type="text" />
            <input placeholder="слово 2" class="newword form-control mb-1" type="text" />
            <input placeholder="слово 3" class="newword form-control mb-1" type="text" />
            <input placeholder="слово 4" class="newword form-control mb-1" type="text" />
            <input placeholder="слово 5" class="newword form-control mb-1" type="text" />
            <input placeholder="слово 6" class="newword form-control mb-1" type="text" />
            <button class="btn btn-primary mt-2 btn-block" id="addwords">Положить слова в шляпу</button>
        </div>
        <div class="container mt-1 mb-1" id="information"></div>
        <div class="container mt-2 mb-4">
            <div>Управление игрой:</div>
            <button class="btn btn-success mb-3 mt-1 btn-block" id="newgame">Начать новый кон</button>
            <button class="btn btn-danger mb-4 btn-block" id="clear">Очистить всесь набор слов</button>
        </div>
        <div class="container mb-2">
            <button class="btn btn-info mb-2 btn-block" id="infobutton">Число слов в игре</button>
            <button class="btn btn-info mb-2 btn-block" id="nullScores">Обнулить очки</button>
        </div>
        <div class="container mb-2">
            <button id="randomiser" class="btn btn-link text-info">Разбить участников на пары</button>
            <div class="ml-4 text-info">
                <p id="randomiser-res"></p>
            </div>
        </div>
    </div>

    <div id="setgamename" class="container p-2 ">
        <div class="text-center py-1 px-2 text-white bg-info guestmessage">
            <h2>Привет👋🏻</h2>
        </div>
        <a class="text-right px-3 text-dark" id="feedback" href="https://forms.gle/8aPdhm4tWjMgmNEX9" target="_blank">Отзыв</a>
        <div class="container form-group my-2 px-3 py-2">
            <h5 class="text-center mb-2">Войдите в игровую комнату</h5>
            <small id="gameroom-info" class="form-text text-muted my-2"></small>
            <input autofocus placeholder="Имя комнаты" value="" type="text" id="selectname" class="form-control my-1 py-1" aria-describedby="gameroom-info">
            <small id="gameroom-title" class="form-text text-muted mx-2 my-2">Придумайте и введите имя игровой комнаты. Другие игроки смогут присоединиться, введя такое-же имя.</small>
            <button id="selectnamebutton" class="btn btn-success btn-block my-2 ">Войти</button>
        </div>
    </div>

    <main>
        <button id="menuopen" class="btn shadow-none">МЕНЮ</button>
        <div id="msg"></div>
        <div id="timer"></div>
    </main>

    <footer>
        <button id="play" class="container btn btn-success btn-lg">Ход</button>
        <button id="next" class="container btn btn-primary btn-lg">Следующее слово</button>
    </footer>

</body>

</html>
