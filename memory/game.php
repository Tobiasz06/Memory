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
                <?php if ($mode === 'Multiplayer'): ?>
                    | Players: <strong><?= $players ?></strong>
                <?php endif; ?>
                | Pairs: <strong><?= $pairs ?></strong>
            </p>
        </div>



        <?php include 'tpl/game_board.php'; ?>

    </main>

<?php include 'tpl/footer.php'; ?>