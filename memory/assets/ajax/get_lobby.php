<!-- Returns the state of a lobby for updating  -->

<?php
$lobbiesFile = __DIR__ . '/../../data/lobbies.json';
$lobbies = file_exists($lobbiesFile) ? json_decode(file_get_contents($lobbiesFile), true) : [];
$lobbyId = $_GET['lobbyId'] ?? '';
if (isset($lobbies[$lobbyId])) {
    echo json_encode($lobbies[$lobbyId]);
} else {
    echo json_encode(['error' => 'Lobby not found']);
}