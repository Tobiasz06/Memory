$(document).ready(function () {
    let flippedCards = [];

    $('.card').on('click', function () {
        // stop als kaart al flipped is
        if ($(this).hasClass('flipped') || flippedCards.length >= 2) return;

        $(this).addClass('flipped');
        flippedCards.push($(this));

        // als er nu 2 kaarten zijn, controleer op match
        if (flippedCards.length === 2) {
            const card1 = flippedCards[0];
            const card2 = flippedCards[1];

            const value1 = card1.data('card');
            const value2 = card2.data('card');

            if (value1 === value2) {
                // match gevonden → laat ze flipped
                flippedCards = [];
            } else {
                // geen match → flip terug na 1 seconde
                setTimeout(() => {
                    card1.removeClass('flipped');
                    card2.removeClass('flipped');
                    flippedCards = [];
                }, 500);
            }
        }
    });
});
