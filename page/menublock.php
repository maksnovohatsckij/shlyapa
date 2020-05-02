<div id="menu">
    <div id="menuclosecontainer" class="container">
        <button id="menuclose" class="btn btn-link">Назад</button>
    </div>
    <div class="container mb-2">
        <div>Добавить слова:</div>
        <input placeholder="слово 1" class="newword form-control mb-1 mt-1" type="text" />
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
