<?php
$mode = $_GET['mode'] ?? 'solo';
$players = $_GET['players'] ?? 2;
$pairs = $_GET['pairs'] ?? 6;

$playerNames = [];
for ($i = 1; $i <= $players; $i++) {
    $name = $_GET["player$i"] ?? "Player $i";
    $playerNames[] = $name !== '' ? $name : "Player $i";
}

// load the top part of the html
include 'tpl/header.php';
?>

<main class="game-area-row">

    <div class="left-panel">
        <div class="top-controls">
            <button onclick="window.location.href='index.php'">◄ Back to menu</button>
        </div>

        <!-- shows when the game ends -->
        <div id="game-over-message" style="display:none;"></div>

        <!-- restart button hidden at first -->
        <button id="restart-button" style="display:none;">🔁 Restart Game</button>

        <!-- load the board with cards -->
        <?php include 'tpl/game_board.php'; ?>
    </div>

    <aside class="right-panel">
        <h2>Scoreboard</h2>

        <!-- shows whose turn it is or solo mode -->
        <div id="turn-indicator" class="mode-label">
            <?php if ($mode === 'multi'): ?>
                Player 1's turn
            <?php else: ?>
                Solo Mode
            <?php endif; ?>
        </div>

        <!-- scoreboard for players or miss counter for solo -->
        <div id="score-board">
            <?php if ($mode === 'multi'): ?>
                <?php foreach ($playerNames as $index => $name): ?>
                    <p><?= htmlspecialchars($name) ?>: <span id="score-player<?= $index + 1 ?>">0</span> points</p>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Misses: <span id="solo-misses">0</span></p>
            <?php endif; ?>
        </div>

        <!-- shows selected settings -->
        <div class="game-mode-info">
            <p>
                Game mode: <strong><?= $mode ?></strong>
                <?php if ($mode === 'multi'): ?>
                    | Players: <strong><?= $players ?></strong>
                <?php endif; ?>
                | Pairs: <strong><?= $pairs ?></strong>
            </p>
        </div>
    </aside>


</main>

<?php include 'tpl/footer.php'; ?>
