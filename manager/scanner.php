<?php
$del_2 = 0;
$format = 'Y-m-d H:i:s';
$now = (new DateTime())->format($format);
$threedayas = mktime(0, 0, 3, 3, 1, 1970);
$directory = '../data/';
$scanned_directory = array_diff(scandir($directory), array('..', '.', ".gitkeep", "log_deleted_files.txt"));
foreach ($scanned_directory as $value) {
    $file_time = date($format, filemtime("$directory" . "$value"));
    $difference = strtotime($now) - strtotime($file_time);
    if ($difference > $threedayas) addFileContent("$directory" . "log_deleted_files.txt", (($del_2++ + 1) . ". " . "$value" . " - deleted, " . (unlink("$directory" . "$value") ? "success" : "fail") . "\r\n"));
}
if ($del_2 > 0) {
    echo ', так же удалены устаревшие файлы';
    addFileContent("$directory" . "log_deleted_files.txt", "$now" . "\r\n\n");
}
