<?php
// Determine number of pairs and difficulty level
$pairs = isset($_GET['pairs']) ? (int)$_GET['pairs'] : 3;

switch ($pairs) {
    case 2: $difficulty = 'easy'; break;
    case 6: $difficulty = 'medium'; break;
    case 10: $difficulty = 'hard'; break;
    default: $difficulty = 'easy';
}

// All possible unique card types
$cardTypes = [
    "apple", "avocado", "banana", "blueberries",
    "cherry", "grape", "kiwi", "lemon", "mango", "peach"
];

// Limit to the number of needed pairs
$selectedCards = array_slice($cardTypes, 0, $pairs);

// Duplicate to form pairs and shuffle
$cards = array_merge($selectedCards, $selectedCards);
shuffle($cards);
?>

<div id="game-board" class="grid <?= $difficulty ?>">
    <?php foreach ($cards as $index => $card): ?>
        <div class="card" data-card="<?= $card ?>" data-card-id="<?= $index ?>">
            <div class="card-inner">
                <div class="card-front">
                    <!-- front face (usually card back) -->
                    <img src="assets/img/card-back.png" alt="card back">
                </div>
                <div class="card-back">
                    <!-- back face (actual fruit) -->
                    <img src="assets/img/<?= $card ?>.png" alt="<?= $card ?>">
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
