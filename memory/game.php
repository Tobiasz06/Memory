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
        <p>
            Game mode: <strong><?= $mode ?></strong>
            <?php if ($mode === 'multi'): ?>
                | Players: <strong><?= $players ?></strong>
            <?php endif; ?>
            | Pairs: <strong><?= $pairs ?></strong>
        </p>
    </div>

    <div id="turn-indicator">Player 1's turn</div>

    <div id="score-board">
        <?php if ($mode === 'multi'): ?>
            <?php for ($i = 1; $i <= $players; $i++): ?>
                <p>Player <?= $i ?>: <span id="score-player<?= $i ?>">0</span> points</p>
            <?php endfor; ?>
        <?php else: ?>
            <p>Turns: <span id="solo-turns">0</span></p>
            <p>Misses: <span id="solo-misses">0</span></p>
        <?php endif; ?>
    </div>


    <div id="game-over-message" style="display:none; font-weight: bold; font-size: 1.2em;"></div>
    <button id="restart-button" style="display:none;">🔁 Restart Game</button>

    <?php include 'tpl/game_board.php'; ?>

</main>

<?php include 'tpl/footer.php'; ?>
