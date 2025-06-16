$(document).ready(function () {
    let flippedCards = [];
    const mode = new URLSearchParams(window.location.search).get("mode") || "solo";

    // multiplayer support
    const numPlayers = mode === 'multi' ? parseInt(new URLSearchParams(window.location.search).get("players")) || 2 : 1;
    let currentPlayerIndex = 0;
    let scores = Array(numPlayers).fill(0);
    let soloMatchedPairs = 0;
    let soloMisses = 0;

    const totalPairs = $('.card').length / 2;


    // update whose turn it is
    function updateTurnIndicator() {
        if (mode === "multi") {
            const name = (window.playerNames && window.playerNames[currentPlayerIndex]) 
                ? window.playerNames[currentPlayerIndex] 
                : `Player ${currentPlayerIndex + 1}`;
            $('#turn-indicator').text(`${name}'s turn`);
            highlightCurrentPlayer();  
        } else {
            $('#turn-indicator').text(`Solo Mode`);
        }
    }

    // update scores
    function updateScores() {
        if (mode === "multi") {
            scores.forEach((score, index) => {
                $(`#score-player${index + 1}`).text(score);
            });
        } else {
            $('#solo-misses').text(soloMisses);
        }
    }

    // switch players
    function switchPlayer() {
        currentPlayerIndex = (currentPlayerIndex + 1) % numPlayers;
        updateTurnIndicator();
    }


    // check if game is over and show winner
    function checkGameOver() {
        let matchedPairs;
        if (mode === "multi") {
            matchedPairs = scores.reduce((a, b) => a + b, 0);
        } else {
            matchedPairs = soloMatchedPairs;
        }
        if (matchedPairs === totalPairs) {
            let message = "";

            if (mode === "multi") {
                const maxScore = Math.max(...scores);
                const winners = scores.map((s, i) => s === maxScore ? i + 1 : null).filter(x => x);
                if (winners.length === 1) {
                    message = `🏆 Player ${winners[0]} wins!`;
                } else {
                    message = `🤝 It's a draw between players: ${winners.join(", ")}`;
                }
            } else {
                message = `You win! Total misses: ${soloMisses}`;
            }

            $('#game-over-message').text(message).fadeIn();
            $('#restart-button').fadeIn();
            $('.card').off('click');
        }
    }

    // highlight the current player
        function highlightCurrentPlayer() {
        if (mode === "multi") {
            for (let i = 0; i < numPlayers; i++) {
                $(`#score-player${i + 1}`).parent().toggleClass('current', i === currentPlayerIndex);
            }
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
                // Match found
                if (mode === "multi") {
                    scores[currentPlayerIndex]++;
                } else {
                    soloMatchedPairs++;
                }
                flippedCards = [];
                updateScores();
                checkGameOver(); // let them continue turn
            } else {
                // Mismatch
                setTimeout(() => {
                    card1.removeClass('flipped');
                    card2.removeClass('flipped');

                    if (mode === "multi") {
                        switchPlayer();
                    } else {
                        soloMisses++;
                    }

                    flippedCards = [];
                    updateScores();
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

// drop down menu for selecting amount of players in multiplayers
document.addEventListener('DOMContentLoaded', function () {
    const modeRadios = document.querySelectorAll('input[name="mode"]');
    const playerSelect = document.getElementById('player-select');

    function togglePlayerSelect() {
        const mode = document.querySelector('input[name="mode"]:checked').value;
        playerSelect.style.display = (mode === 'multi') ? 'block' : 'none';
    }

    // on load
    togglePlayerSelect();

    // on change
    modeRadios.forEach(radio => {
        radio.addEventListener('change', togglePlayerSelect);
    });
});


//fades the footer out after 5 seconds to make more space for the grid
function fadeOutFooter(afterMs = 5000) {
  setTimeout(() => {
    const footer = document.querySelector('footer');
    if (footer) {
      footer.classList.add('fade-out');

      footer.addEventListener('animationend', () => {
        footer.style.display = 'none';
      }, { once: true });
    }
  }, afterMs);
}

window.addEventListener('load', () => {
  fadeOutFooter(10000);
});

