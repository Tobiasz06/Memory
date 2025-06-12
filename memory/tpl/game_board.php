<?php
// Alle mogelijke kaarten (8 unieke paren)
$cards = [
    "apple", "banana", "grape", "kiwi",
    "lemon", "mango", "peach", "cherry"
];

// Dubbel de array om paren te maken
$cards = array_merge($cards, $cards);

// Schud de kaarten
shuffle($cards);
?>

<div id="game-board">
    <?php foreach ($cards as $card): ?>
        <div class="card" data-card="<?= $card ?>">
            <div class="card-inner">
                <div class="card-front"></div>
                <div class="card-back">
                    <img src="assets/img/<?= $card ?>.png" alt="<?= $card ?>">
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
