<!-- AJAX handler for creating a lobby and saving that to lobbies.json -->

<?php
$lobbiesFile = __DIR__ . '/../../data/lobbies.json';
$lobbies = file_exists($lobbiesFile) ? json_decode(file_get_contents($lobbiesFile), true) : [];
$lobbyId = uniqid('lobby_');
$name = trim($_POST['name'] ?? '');
$password = $_POST['password'] ?? '';
$hostUsername = trim($_POST['hostUsername'] ?? '');
$difficulty = $_POST['difficulty'] ?? '6';
$maxPlayers = (int)($_POST['maxPlayers'] ?? 2);

if (!$name || !$password || !$hostUsername) {
    echo json_encode(['success' => false, 'message' => 'Missing fields']);
    exit;
}
$lobbies[$lobbyId] = [
    'id' => $lobbyId,
    'name' => $name,
    'password' => password_hash($password, PASSWORD_DEFAULT),
    'difficulty' => $difficulty,
    'maxPlayers' => $maxPlayers,
    'players' => [ $hostUsername ],
    'state' => [
        'scores' => [0],
        'currentPlayerIndex' => 0,
        'flipped' => [],
        'matched' => [],
    ],
    'started' => false,
];
file_put_contents($lobbiesFile, json_encode($lobbies));
echo json_encode(['success' => true, 'lobbyId' => $lobbyId]);