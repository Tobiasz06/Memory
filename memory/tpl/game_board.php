<div id="game-board" class="grid">
    <?php
    $totalCards = $pairs * 2;
    for ($i = 0; $i < $totalCards; $i++) {
        // Temporary command, image is in the working.
        echo '<div class="card" data-card-id="'.$i.'">
                <img src="assets/img/card-back.png" alt="card back">
              </div>';
    }
    ?>
</div>