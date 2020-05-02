<?php
$msg = $_POST['message'];
$task = $_POST['task'];
$filename = $_POST['gamename'] ? $_POST['gamename'] : "game";
$filename2 = "$filename" . "_playroom_active";
$filepath = "../content/" . "$filename" . ".txt";
$filepath2 = "../content/" . "$filename2" . ".txt";

switch ($task) {
    case "play": //
        echo readFileContent($filepath2);
        break;

    case "next": //
        writeFileContent($filepath2, $msg);
        echo readFileContent($filepath2);
        break;

    case "newgame": //
        $mytext = readFileContent($filepath);
        echo $mytext ? (writeFileContent($filepath2, $mytext) ? 'Игра начата' : 'Ошибка при создании нового кона') : ('Ошибка, в игре нет слов');
        break;

    case "clear": //
        clearAllFiles($filepath, $filepath2);
        break;

    case "addwords": //
        echo addFileContent($filepath, $msg) ? 'Данные в файл успешно занесены' : 'Ошибка при записи в файл';
        break;

    case "info": //
        echo readFileContent($filepath);
        break;

    default:
        echo "неверный запрос";
        break;
}

function readFileContent($filepath)
{
    return file_get_contents($filepath);
}

function addFileContent($filepath, $mytext)
{
    return file_put_contents($filepath, $mytext, FILE_APPEND);
}

function writeFileContent($filepath, $mytext)
{
    return file_put_contents($filepath, $mytext);
}

function clearAllFiles($filepath, $filepath2)
{
    $del = 0;
    if (unlink($filepath)) $del++;
    if (unlink($filepath2))  $del++;
    echo ($del > 0) ? "Набор слов успешно очищен" : "Данные уже очищены";
}
