<?php
$mode = $_GET['mode'] ?? 'solo';
$players = $_GET['players'] ?? 2;
$pairs = $_GET['pairs'] ?? 6;

include 'tpl/header.php';
echo "<p>Game mode: <strong>$mode</strong> | Players: <strong>$players</strong> | Pairs: <strong>$pairs</strong></p>";

include 'tpl/game_board.php';
include 'tpl/footer.php';
?>