<?php
$lobbiesFile = __DIR__ . '/../../data/lobbies.json';
$lobbies = file_exists($lobbiesFile) ? json_decode(file_get_contents($lobbiesFile), true) : [];
$lobbyId = $_POST['lobbyId'] ?? '';
$state = isset($_POST['state']) ? json_decode($_POST['state'], true) : null;

if (!$lobbyId || !isset($lobbies[$lobbyId]) || !$state) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$lobbies[$lobbyId]['state'] = $state;
file_put_contents($lobbiesFile, json_encode($lobbies));
echo json_encode(['success' => true]);