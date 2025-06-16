<?php
include 'tpl/header.php';

// Get mode, players, and pairs from GET parameters
$mode = $_GET['mode'] ?? 'solo';
$players = isset($_GET['players']) ? (int)$_GET['players'] : 2;
$pairs = $_GET['pairs'] ?? 6;

// Prepare form action to go to game.php with all parameters
$action = "game.php?mode=" . urlencode($mode) . "&players=" . urlencode($players) . "&pairs=" . urlencode($pairs);
?>

<div class="container-box">
    <form action="<?= $action ?>" method="get" id="username-form">
        <input type="hidden" name="mode" value="<?= htmlspecialchars($mode) ?>">
        <input type="hidden" name="players" value="<?= htmlspecialchars($players) ?>">
        <input type="hidden" name="pairs" value="<?= htmlspecialchars($pairs) ?>">

        <?php if ($mode === 'multi'): ?>
            <label>Enter player names:</label><br>
            <?php for ($i = 1; $i <= $players; $i++): ?>
                <input type="text" name="player<?= $i ?>" placeholder="Player <?= $i ?> name"><br>
            <?php endfor; ?>
        <?php else: ?>
            <label>Your name:</label>
            <input type="text" name="player1" placeholder="Your name"><br>
        <?php endif; ?>

        <br>
        <button class="button" type="submit">Start Game</button>
    </form>
</div>

<?php include 'tpl/footer.php'; ?>