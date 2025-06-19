<!-- Returns avaliabe lobbies as a list -->

<?php
$lobbiesFile = __DIR__ . '/../../data/lobbies.json';
$lobbies = file_exists($lobbiesFile) ? json_decode(file_get_contents($lobbiesFile), true) : [];
$out = [];
foreach ($lobbies as $lobby) {
    if (count($lobby['players']) < $lobby['maxPlayers']) {
        $out[] = [
            'id' => $lobby['id'],
            'name' => $lobby['name'],
            'players' => $lobby['players'],
            'maxPlayers' => $lobby['maxPlayers'],
            'difficulty' => $lobby['difficulty']
        ];
    }
}
echo json_encode($out);