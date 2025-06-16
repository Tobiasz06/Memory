<?php
$bodyClass = 'username-page';
$showTopControls = true;
include 'tpl/header.php';

// Get mode, players, and pairs from GET parameters
$gamemode = $_GET['mode'] ?? '';
$players = (int)($_GET['players'] ?? 1);
$pairs = $_GET['pairs'] ?? '';

// Always show at least one username input
$numInputs = max(1, $players);
?>
<div class="center-vertical-wrapper">
    <form method="get" action="game.php" class="container-box" id="username-form">
        <input type="hidden" name="mode" value="<?php echo htmlspecialchars($gamemode); ?>">
        <input type="hidden" name="players" value="<?php echo htmlspecialchars($players); ?>">
        <input type="hidden" name="pairs" value="<?php echo htmlspecialchars($pairs); ?>">
        <?php
        for ($i = 1; $i <= $numInputs; $i++) {
            echo "<label class='username-label'>Username for Player $i:</label>
                <input class='username-input' type='text' name='username[]' required autocomplete='off'><br>";
        }
        ?>
        <button type="submit" class="start-game-btn">Start Game</button>
    </form>
</div>
<?php include 'tpl/footer.php'; ?>