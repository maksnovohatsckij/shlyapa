<?php
$task = $_POST['task'];
$msg = $_POST['message'];
$filename1 = $_POST['gameroom'] ? $_POST['gameroom'] : "default";
$filename2 = "$filename1" . "#playroom_active";
$filename3 = "$filename1" . "#players";
$filepath1 = file_path($filename1);
$filepath2 = file_path($filename2);
$filepath3 = file_path($filename3);

switch ($task) {
    case "play": //
        echo readFileContent($filepath2);
        break;

    case "next": //
        $text = str_replace_once($msg, "", readFileContent($filepath2));
        writeFileContent($filepath2, $text);
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

    case "getPlayers": //
        echo readFileContent($filepath3);
        break;

    case "setPlayers": //
        echo ($msg)
            ? (writeFileContent($filepath3, $msg) ? 'Данные в файл успешно занесены' : 'Ошибка при записи в файл')
            : ("Пустой набор слов");
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
    global $filepath1, $filepath2, $filepath3;
    $del = 0;
    if (file_exists($filepath1)) if (unlink($filepath1)) $del++;
    if (file_exists($filepath2)) if (unlink($filepath2)) $del++;
    if (file_exists($filepath3)) if (unlink($filepath3)) $del++;
    echo ($del > 0) ? "Данные успешно очищены" : "Данные уже очищены";
    include "./scanner.php";
}

function getLastAction()
{
    global $filepath1, $filepath2, $filepath3;
    $t1 = file_exists($filepath1) ? filemtime($filepath1) : false;
    $t2 = file_exists($filepath2) ? filemtime($filepath2) : false;
    $t3 = file_exists($filepath3) ? filemtime($filepath3) : false;
    if ($t1 || $t2 || $t3) return date('j M H:i', max(max($t1, $t2), $t3));
}

function file_path($filename)
{
    return "../data/" . "$filename";
}

function str_replace_once($search, $replace, $text)
{
    $pos = strpos($text, $search);
    return $pos !== false ? substr_replace($text, $replace, $pos, strlen($search)) : $text;
}
