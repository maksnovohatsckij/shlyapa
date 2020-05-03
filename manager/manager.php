<?php
$task = $_POST['task'];
$msg = $_POST['message'];
$filename1 = $_POST['gamename'] ? $_POST['gamename'] : "game";
$filename2 = "$filename1" . "_playroom_active";
$filename3 = "$filename1" . "_last_action";
$filepath1 = file_path($filename1);
$filepath2 = file_path($filename2);
$filepath3 = file_path($filename3);

switch ($task) {
    case "play": //
        echo readFileContent($filepath2);
        break;

    case "next": //
        writeFileContent($filepath2, $msg);
        echo readFileContent($filepath2);
        log_action();
        break;

    case "newgame": //
        $text = readFileContent($filepath1);
        echo $text
            ? (writeFileContent($filepath2, $text) ? 'Игра начата' : 'Ошибка при создании нового кона')
            : ('Ошибка, в игре нет слов');
        log_action();
        break;

    case "clear": //
        clearAllFiles();
        break;

    case "addwords": //
        echo ($msg)
            ? (addFileContent($filepath1, $msg) ? 'Данные в файл успешно занесены' : 'Ошибка при записи в файл')
            : ("Пустой набор слов");
        log_action();
        break;

    case "info": //
        echo readFileContent($filepath1);
        break;

    case "getactions": //
        echo readFileContent($filepath3);
        break;

    default:
        echo "неверный запрос";
        break;
}

function readFileContent($filepath)
{
    return file_get_contents($filepath);
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
    if (unlink($filepath1)) $del++;
    if (unlink($filepath2))  $del++;
    echo ($del > 0) ? "Набор слов успешно очищен" : "Данные уже очищены";
}

function file_path($filename)
{
    return "../content/" . "$filename" . ".txt";
}

function log_action()
{
    global $filepath3;
    file_put_contents($filepath3, date('j M H:i'));
}
