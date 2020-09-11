<?php
$del_2 = 0;
$three_days_ago = strtotime("-3 days");
$directory = '../data/';
$logfile = "$directory" . "log_deleted_files.txt";
$scanned_directory = array_diff(scandir($directory), array('..', '.', ".gitkeep", "log_deleted_files.txt"));
foreach ($scanned_directory as $value) {
    $filetime = filemtime("$directory" . "$value");
    if ($filetime < $three_days_ago) addFileContent($logfile, (($del_2++ + 1) . ". $value - " . (unlink("$directory" . "$value") ? "del" : "fail") . "\r\n"));
}
if ($del > 0) {
    global $filename1, $filename2, $filename3;
    addFileContent($logfile, ($del > 1 ? ($del > 2 ? "$filename1, $filename2, $filename3" : "$filename1, $filename2") : "$filename1") . " - del\r\n");
    if (file_exists($logfile)) if (filesize($logfile) > 1024 * 20) unlink($logfile);
}
if ($del_2 > 0) {
    echo ', так же удалены устаревшие файлы';
    addFileContent($logfile, date('Y-m-d H:i:s') . "\r\n\n");
    if (file_exists($logfile)) if (filesize($logfile) > 1024 * 20) unlink($logfile);
}
