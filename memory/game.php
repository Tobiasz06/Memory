<?php
$mode = $_GET['mode'] ?? 'solo';
$players = $_GET['players'] ?? 2;
$pairs = $_GET['pairs'] ?? 6;

include 'tpl/header.php';
?>

<!-- Game Area with background -->
<main class="game-area">

    <div class="top-controls">
        <button onclick="window.location.href='index.php'">◄ Back to menu</button>
    </div>

    <!-- Game Info -->
    <div class="game-mode-info">
        <p>Game mode: <strong><?= $mode ?></strong> | Players: <strong><?= $players ?></strong> | Pairs: <strong><?= $pairs ?></strong></p>
    </div>

    <div id="turn-indicator">Player 1's turn</div>

    <div id="score-board">
        <p>Player 1: <span id="score-player1">0</span> points</p>
        <p>Player 2: <span id="score-player2">0</span> points</p>
    </div>

    <div id="game-over-message" style="display:none; font-weight: bold; font-size: 1.2em;"></div>
    <button id="restart-button" style="display:none;">🔁 Restart Game</button>

    <?php include 'tpl/game_board.php'; ?>

</main>

<?php include 'tpl/footer.php'; ?>
