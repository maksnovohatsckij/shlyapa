<?php
session_start();
if ($_REQUEST) {
    if ($_POST["task"] == "getScores") echo $_SESSION["scores"];
    elseif ($_POST["task"] == "setScores" && isset($_POST["scores"])) $_SESSION["scores"] = $_POST["scores"];
    exit;
}
