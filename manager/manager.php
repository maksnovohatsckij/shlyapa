<?php
$task = $_POST['task'];
$msg = $_POST['message'];
$filename1 = $_POST['gameroom'] ? $_POST['gameroom'] : "default";
$filename2 = "$filename1" . "#playroom_active";
$filepath1 = file_path($filename1);
$filepath2 = file_path($filename2);

switch ($task) {
    case "play": //
        echo readFileContent($filepath2);
        break;

    case "next": //
        writeFileContent($filepath2, $msg);
        echo readFileContent($filepath2);
        break;

    case "newgame": //
        $text = readFileContent($filepath1);
        echo $text
            ? (writeFileContent($filepath2, $text) ? 'Игра начата' : 'Ошибка при создании нового кона')
            : ('Ошибка, в игре нет слов');
        break;

    case "clear": //
        clearAllFiles();
        break;

    case "addwords": //
        echo ($msg)
            ? (addFileContent($filepath1, $msg) ? 'Данные в файл успешно занесены' : 'Ошибка при записи в файл')
            : ("Пустой набор слов");
        break;

    case "info": //
        echo readFileContent($filepath1);
        break;

    case "getactions": //
        echo getLastAction();
        break;

    default:
        echo "неверный запрос";
        break;
}

function readFileContent($filepath)
{
    if (file_exists($filepath)) return file_get_contents($filepath);
}

function addFileContent($filepath, $text)
{
    return file_put_contents($filepath, $text, FILE_APPEND);
}

function writeFileContent($filepath, $text)
{
    return file_put_contents($filepath, $text);
}

function clearAllFiles()
{
    global $filepath1, $filepath2;
    $del = 0;
    if (file_exists($filepath1)) if (unlink($filepath1)) $del++;
    if (file_exists($filepath2)) if (unlink($filepath2))  $del++;
    echo ($del > 0) ? "Набор слов успешно очищен" : "Данные уже очищены";
    include "./scanner.php";
}

function getLastAction()
{
    global $filepath1, $filepath2;
    if (file_exists($filepath1)) return date('j M H:i', max(filemtime($filepath1), filemtime($filepath2)));
}

function file_path($filename)
{
    return "../data/" . "$filename";
}
