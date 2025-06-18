<?php
$lobbyId = $_GET['lobby'] ?? null;
$playerIndex = $_GET['player'] ?? null;

if ($lobbyId) {
    $lobbiesFile = __DIR__ . '/data/lobbies.json';
    $lobbies = file_exists($lobbiesFile) ? json_decode(file_get_contents($lobbiesFile), true) : [];
    $lobby = $lobbies[$lobbyId] ?? null;
    if (!$lobby) {
        die('Lobby not found.');
    }
    $playerNames = $lobby['players'];
    $mode = 'multi';
    $players = count($playerNames);
    $pairs = $lobby['difficulty'] ?? 6;
    $maxPlayers = $lobby['maxPlayers'];
    $isHost = ($playerIndex == 0);

    // If the player is not in the lobby, show an error
    if (!in_array($playerNames[$playerIndex] ?? null, $playerNames)) {
        die('You are not a member of this lobby.');
    }

    // Wait for enough players or for host to start
    if (count($playerNames) < $maxPlayers || !$lobby['started']) {
        include 'tpl/header.php';
        ?>
        <div class="center-vertical-wrapper">
            <div class="top-controls">
                <button onclick="window.location.href='index.php'">◄ Back to menu</button>
            </div>
            <div class="container-box waiting-lobby">
                <h2>Lobby: <?= htmlspecialchars($lobby['name']) ?></h2>
                <p>Players joined:</p>
                <ul id="player-list">
                    <?php foreach ($playerNames as $name): ?>
                        <li><?= htmlspecialchars($name) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p id="waiting-msg">
                    <?php if (count($playerNames) < $maxPlayers): ?>
                        Waiting for <?= $maxPlayers - count($playerNames) ?> more player(s)...
                    <?php else: ?>
                        All players joined!
                    <?php endif; ?>
                </p>
                <?php if ($isHost): ?>
                    <button id="start-game-btn" <?= (count($playerNames) < $maxPlayers) ? 'disabled' : '' ?>>Start Game</button>
                <?php else: ?>
                    <p>Waiting for host to start the game...</p>
                <?php endif; ?>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
$(function() {
    function updateLobby() {
        $.get('assets/ajax/get_lobby.php', { lobbyId: '<?= $lobbyId ?>' }, function(lobby) {
            // Update player list
            let ul = '';
            (lobby.players || []).forEach(function(name) {
                ul += '<li>' + $('<div>').text(name).html() + '</li>';
            });
            $('#player-list').html(ul);

            // Update waiting message
            if (lobby.players.length < lobby.maxPlayers) {
                $('#waiting-msg').text('Waiting for ' + (lobby.maxPlayers - lobby.players.length) + ' more player(s)...');
            } else {
                $('#waiting-msg').text('All players joined!');
            }

            // Enable/disable start button for host
            <?php if ($isHost): ?>
            $('#start-game-btn').prop('disabled', (lobby.players.length < lobby.maxPlayers));
            <?php endif; ?>

            // If started, reload for everyone
            if (lobby.started) {
                location.reload();
            }
        }, 'json');
    }

    setInterval(updateLobby, 1000);
    updateLobby();

    <?php if ($isHost): ?>
    // Attach the handler ONCE, after DOM is ready
    $('#start-game-btn').off('click').on('click', function() {
        $('#start-game-btn').prop('disabled', true); // Prevent double-click
        $.post('assets/ajax/start_lobby.php', { lobbyId: '<?= $lobbyId ?>' }, function(res) {
            // The polling will reload the page for everyone
        });
    });
    <?php endif; ?>
});
        </script>
        <?php include 'tpl/footer.php'; exit;
    }
} else {
    // fallback: solo or classic multi
    $mode = $_GET['mode'] ?? 'solo';
    $players = $_GET['players'] ?? 2;
    $pairs = $_GET['pairs'] ?? 6;
    $playerNames = [];
    for ($i = 1; $i <= $players; $i++) {
        $name = $_GET["player$i"] ?? "Player $i";
        $playerNames[] = $name !== '' ? $name : "Player $i";
    }
    // Only use username[] from GET if NOT in online mode
    if (!$lobbyId && isset($_GET['username'])) {
        $playerNames = $_GET['username'];
        for ($i = 0; $i < $players; $i++) {
            $name = $playerNames[$i] ?? "Player " . ($i + 1);
            $playerNames[$i] = $name !== '' ? $name : "Player " . ($i + 1);
        }
    }
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
        <?php include __DIR__ . '/tpl/game_board.php'; ?>
    </div>

    <aside class="right-panel">
        <h2>Scoreboard</h2>

        <!-- shows whose turn it is or solo mode -->
        <div id="turn-indicator" class="mode-label">
            <?php if ($lobbyId): // Always show multiplayer UI for online games ?>
                <span id="player-turn-label"><?= htmlspecialchars($playerNames[0]) ?>'s turn</span>
            <?php elseif ($mode === 'multi'): ?>
                <span id="player-turn-label"><?= htmlspecialchars($playerNames[0]) ?>'s turn</span>
            <?php else: ?>
                Solo Mode
            <?php endif; ?>
        </div>

        <!-- scoreboard for players or miss counter for solo -->
        <div id="score-board">
            <?php if ($lobbyId || $mode === 'multi'): ?>
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
                Game mode: <strong><?= htmlspecialchars($mode) ?></strong>
                <?php if ($mode === 'multi'): ?>
                    | Players: <strong><?= htmlspecialchars($players) ?></strong>
                <?php endif; ?>
                | Pairs: <strong><?= htmlspecialchars($pairs) ?></strong>
            </p>
        </div>
    </aside>

</main>

<?php include 'tpl/footer.php'; ?>

<script>
    window.playerNames = <?= json_encode($playerNames) ?>;
</script>