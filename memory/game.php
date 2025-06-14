<?php
$mode = $_GET['mode'] ?? 'solo';
$players = $_GET['players'] ?? 2;
$pairs = $_GET['pairs'] ?? 6;

include 'tpl/header.php';
?>

<main class="game-area-row">

    <div class="left-panel">
        <div class="top-controls">
            <button onclick="window.location.href='index.php'">◄ Back to menu</button>
        </div>

        <div id="game-over-message" style="display:none;"></div>
        <button id="restart-button" style="display:none;">🔁 Restart Game</button>

        <?php include 'tpl/game_board.php'; ?>
    </div>

    <aside class="right-panel">
        <h2>Scoreboard</h2>

        <div id="turn-indicator" class="mode-label">
            <?php if ($mode === 'multi'): ?>
                Player 1's turn
            <?php else: ?>
                Solo Mode
            <?php endif; ?>
        </div>

        <div id="score-board">
            <?php if ($mode === 'multi'): ?>
                <?php for ($i = 1; $i <= $players; $i++): ?>
                    <p>Player <?= $i ?>: <span id="score-player<?= $i ?>">0</span> points</p>
                <?php endfor; ?>
            <?php else: ?>
                <p>Misses: <span id="solo-misses">0</span></p>
            <?php endif; ?>
        </div>

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
