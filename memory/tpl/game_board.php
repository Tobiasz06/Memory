<!-- Generates the grid in which the cards are played -->

<?php
// If in online mode, use the board from the lobby state
if (isset($lobby) && isset($lobby['state']['board'])) {
    $cards = $lobby['state']['board'];
    $pairs = count($cards) / 2;
    switch ($pairs) {
        case 3: $difficulty = 'easy'; break;
        case 6: $difficulty = 'medium'; break;
        case 10: $difficulty = 'hard'; break;
        default: $difficulty = 'medium';
    }
} else {
    $pairs = isset($_GET['pairs']) ? (int)$_GET['pairs'] : 3;
    switch ($pairs) {
        case 3: $difficulty = 'easy'; break;
        case 6: $difficulty = 'medium'; break;
        case 10: $difficulty = 'hard'; break;
        default: $difficulty = 'medium';
    }
    $cardTypes = [
        "apple", "avocado", "banana", "blueberries",
        "cherry", "grape", "kiwi", "lemon", "mango", "peach"
    ];
    $selectedCards = array_slice($cardTypes, 0, $pairs);
    $cards = array_merge($selectedCards, $selectedCards);
    shuffle($cards);
}
?>
<div id="game-board" class="grid <?= $difficulty ?>">
    <?php foreach ($cards as $index => $card): ?>
        <div class="card" data-card="<?= $card ?>" data-card-id="<?= $index ?>">
            <div class="card-inner">
                <div class="card-front">
                    <img src="assets/img/card-back.png" alt="card back">
                </div>
                <div class="card-back">
                    <img src="assets/img/<?= $card ?>.png" alt="<?= $card ?>">
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>