<?php
$pairs = isset($_GET['pairs']) ? (int)$_GET['pairs'] : 3;

switch ($pairs) {
    case 2: $difficulty = 'easy'; break;
    case 6: $difficulty = 'medium'; break;
    case 10: $difficulty = 'hard'; break;
    default: $difficulty = 'easy';
}
?>

<div id="game-board" class="grid <?= $difficulty ?>">
    <?php
    $totalCards = $pairs * 2;
    for ($i = 0; $i < $totalCards; $i++) {
        echo '<div class="card" data-card-id="'.$i.'">
                <img src="assets/img/card-back.png" alt="card back">
              </div>';
    }
    ?>
</div>
