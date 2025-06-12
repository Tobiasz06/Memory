<?php
// all possible cards
$cards = [
    "apple", "banana", "grape", "kiwi",
    "lemon", "mango", "peach", "cherry"
];

// double the array to creat cards
$cards = array_merge($cards, $cards);

// shuffel the cards
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
