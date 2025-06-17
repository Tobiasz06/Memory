<?php
$lobbiesFile = __DIR__ . '/../../data/lobbies.json';
$lobbies = file_exists($lobbiesFile) ? json_decode(file_get_contents($lobbiesFile), true) : [];
$lobbyId = $_POST['lobbyId'] ?? '';
$password = $_POST['password'] ?? '';
$username = trim($_POST['username'] ?? '');

if (!$lobbyId || !$password || !$username) {
    echo json_encode(['success' => false, 'message' => 'Missing fields']);
    exit;
}
if (!isset($lobbies[$lobbyId])) {
    echo json_encode(['success' => false, 'message' => 'Lobby not found']);
    exit;
}
$lobby = &$lobbies[$lobbyId];
if (!password_verify($password, $lobby['password'])) {
    echo json_encode(['success' => false, 'message' => 'Wrong password']);
    exit;
}
if (count($lobby['players']) >= $lobby['maxPlayers']) {
    echo json_encode(['success' => false, 'message' => 'Lobby full']);
    exit;
}
$lobby['players'][] = $username;
$lobby['state']['scores'][] = 0;
file_put_contents($lobbiesFile, json_encode($lobbies));
echo json_encode(['success' => true, 'playerIndex' => count($lobby['players']) - 1]);