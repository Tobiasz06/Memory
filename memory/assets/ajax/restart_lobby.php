<?php
// Handles restarting of the lobby, ensures board is shuffled again
$lobbiesFile = __DIR__ . '/../../data/lobbies.json';
$lobbies = file_exists($lobbiesFile) ? json_decode(file_get_contents($lobbiesFile), true) : [];
$lobbyId = $_POST['lobbyId'] ?? '';

if (isset($lobbies[$lobbyId])) {
    $lobby = &$lobbies[$lobbyId];
    $pairs = (int)($lobby['difficulty'] ?? 6);
    $cardTypes = [
        "apple", "avocado", "banana", "blueberries",
        "cherry", "grape", "kiwi", "lemon", "mango", "peach"
    ];
    $selectedCards = array_slice($cardTypes, 0, $pairs);
    $cards = array_merge($selectedCards, $selectedCards);
    shuffle($cards);

    // Reset state
    $lobby['state'] = [
        'scores' => array_fill(0, count($lobby['players']), 0),
        'currentPlayerIndex' => 0,
        'flipped' => [],
        'matched' => [],
        'board' => $cards
    ];
    $lobby['started'] = true;

    file_put_contents($lobbiesFile, json_encode($lobbies));
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}