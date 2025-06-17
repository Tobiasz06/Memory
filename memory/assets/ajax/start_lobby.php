<?php
$lobbiesFile = __DIR__ . '/../../data/lobbies.json';
$lobbies = file_exists($lobbiesFile) ? json_decode(file_get_contents($lobbiesFile), true) : [];
$lobbyId = $_POST['lobbyId'] ?? '';
if (isset($lobbies[$lobbyId])) {
    // Generate the board ONCE, only when starting
    $pairs = (int)($lobbies[$lobbyId]['difficulty'] ?? 6);
    $cardTypes = [
        "apple", "avocado", "banana", "blueberries",
        "cherry", "grape", "kiwi", "lemon", "mango", "peach"
    ];
    $selectedCards = array_slice($cardTypes, 0, $pairs);
    $cards = array_merge($selectedCards, $selectedCards);
    shuffle($cards);
    $lobbies[$lobbyId]['state']['board'] = $cards;

    $lobbies[$lobbyId]['started'] = true;
    file_put_contents($lobbiesFile, json_encode($lobbies));
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}