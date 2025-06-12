$(document).ready(function () {
    let flippedCards = [];
    let currentPlayer = "Player 1";
    let scores = {
        "Player 1": 0,
        "Player 2": 0
    };

    const totalPairs = $('.card').length / 2;

    // update whose turn it is
    function updateTurnIndicator() {
        $('#turn-indicator').text(`${currentPlayer}'s turn`);
    }

    // update score display
    function updateScores() {
        $('#score-player1').text(scores["Player 1"]);
        $('#score-player2').text(scores["Player 2"]);
    }

    // switch to the other player
    function switchPlayer() {
        currentPlayer = (currentPlayer === "Player 1") ? "Player 2" : "Player 1";
        updateTurnIndicator();
    }

    // check if game is over and show winner
    function checkGameOver() {
        const matchedPairs = scores["Player 1"] + scores["Player 2"];
        if (matchedPairs === totalPairs) {
            let message = "";
            if (scores["Player 1"] > scores["Player 2"]) {
                message = "🏆 Player 1 wins!";
            } else if (scores["Player 2"] > scores["Player 1"]) {
                message = "🏆 Player 2 wins!";
            } else {
                message = "🤝 It's a draw!";
            }

            $('#game-over-message').text(message).fadeIn();
            $('#restart-button').fadeIn();
            $('.card').off('click'); // Disable clicking
        }
    }

    // handle card clicks
    $('.card').on('click', function () {
        if ($(this).hasClass('flipped') || flippedCards.length >= 2) return;

        $(this).addClass('flipped');
        flippedCards.push($(this));

        if (flippedCards.length === 2) {
            const card1 = flippedCards[0];
            const card2 = flippedCards[1];

            const value1 = card1.data('card');
            const value2 = card2.data('card');

            if (value1 === value2) {
                scores[currentPlayer] += 1;
                updateScores();
                flippedCards = [];
                checkGameOver();
            } else {
                setTimeout(() => {
                    card1.removeClass('flipped');
                    card2.removeClass('flipped');
                    flippedCards = [];
                    switchPlayer();
                }, 1000);
            }
        }
    });

    // restart game button handler
    $('#restart-button').on('click', function () {
        location.reload(); // simple reload to reset the board
    });

    // initialize UI
    updateTurnIndicator();
    updateScores();
});
