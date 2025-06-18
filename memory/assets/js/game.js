$(document).ready(function () {
    let flippedCards = [];
    const urlParams = new URLSearchParams(window.location.search);
    const lobbyId = urlParams.get('lobby');
    const playerIndex = parseInt(urlParams.get('player'), 10);
    let isOnline = !!lobbyId;
    const mode = isOnline ? "multi" : (urlParams.get("mode") || "solo");

    // multiplayer support
    let windowPlayerNames = window.playerNames || [];
    const numPlayers = isOnline
        ? (windowPlayerNames.length || 2)
        : (mode === 'multi' ? parseInt(urlParams.get("players")) || 2 : 1);
    let currentPlayerIndex = 0;
    let scores = Array(numPlayers).fill(0);
    let soloMatchedPairs = 0;
    let soloMisses = 0;
    const totalPairs = $('.card').length / 2;

    let gameState = null;

    // --- UI UPDATERS ---
    function updateTurnIndicator() {
        if (isOnline && gameState && window.playerNames) {
            const name = window.playerNames[gameState.currentPlayerIndex] || `Player ${gameState.currentPlayerIndex + 1}`;
            $('#player-turn-label').text(`${name}'s turn`);
            highlightCurrentPlayer(gameState.currentPlayerIndex);
        } else if (mode === "multi") {
            const name = window.playerNames?.[currentPlayerIndex] || `Player ${currentPlayerIndex + 1}`;
            $('#player-turn-label').text(`${name}'s turn`);
            highlightCurrentPlayer(currentPlayerIndex);
        } else {
            $('#turn-indicator').text(`Solo Mode`);
        }
    }

    function updateScores() {
        if (isOnline && gameState && gameState.scores) {
            gameState.scores.forEach((score, index) => {
                $(`#score-player${index + 1}`).text(score);
            });
        } else if (mode === "multi") {
            scores.forEach((score, index) => {
                $(`#score-player${index + 1}`).text(score);
            });
        } else {
            $('#solo-misses').text(soloMisses);
        }
    }

    function highlightCurrentPlayer(idx) {
        if (isOnline && window.playerNames) {
            for (let i = 0; i < window.playerNames.length; i++) {
                $(`#score-player${i + 1}`).parent().toggleClass('current', i === idx);
            }
        } else if (mode === "multi") {
            for (let i = 0; i < numPlayers; i++) {
                $(`#score-player${i + 1}`).parent().toggleClass('current', i === idx);
            }
        }
    }

    // --- ONLINE SYNC ---
    function fetchLobbyState(callback) {
        if (!isOnline) return;
        $.get('assets/ajax/get_lobby.php', { lobbyId: lobbyId }, function(lobby) {
            if (lobby && lobby.state) {
                callback(lobby.state, lobby.players);
            }
        }, 'json');
    }

    function syncToLobby(state) {
        if (!isOnline) return;
        $.post('assets/ajax/update_lobby.php', {
            lobbyId: lobbyId,
            state: JSON.stringify(state)
        });
    }

    // --- BOARD STATE SYNC ---
    function updateBoardFromState() {
        if (isOnline && gameState) {
            // Remove all flip/match classes
            $('.card').removeClass('flipped matched');
            // Mark matched cards
            (gameState.matched || []).forEach(idx => {
                $(`.card[data-card-id="${idx}"]`).addClass('matched');
            });
            // Flip currently flipped cards (but not matched)
            (gameState.flipped || []).forEach(idx => {
                if (!(gameState.matched || []).includes(idx)) {
                    $(`.card[data-card-id="${idx}"]`).addClass('flipped');
                }
            });
            flippedCards = []; // Always reset local flips to match server
        }
    }

    // --- POLLING FOR ONLINE ---
    if (isOnline) {
        setInterval(function() {
            fetchLobbyState(function(state, players) {
                gameState = state;
                window.playerNames = players;
                updateTurnIndicator();
                updateScores();
                updateBoardFromState();
                checkGameOver();
            });
        }, 1000);
    } else {
        updateTurnIndicator();
        updateScores();
    }

    // --- TURN LOGIC ---
    function isMyTurn() {
        return isOnline ? (gameState && gameState.currentPlayerIndex === playerIndex) : true;
    }

    // --- CARD CLICK HANDLER ---
    $(document).off('click', '.card').on('click', '.card', function () {
        if (isOnline && !gameState) return; // Wait for state to load
        if (typeof isMyTurn === 'function' && !isMyTurn()) return;
        const cardIndex = parseInt($(this).attr('data-card-id'));
        if ($(this).hasClass('flipped') || $(this).hasClass('matched')) return;
        if (isOnline && gameState.flipped.length >= 2) return;
        if (!isOnline && flippedCards.length >= 2) return;

        if (isOnline) {
            if (!gameState.flipped.includes(cardIndex) && !gameState.matched.includes(cardIndex)) {
                gameState.flipped.push(cardIndex);
                updateBoardFromState();
                syncToLobby(gameState);

                // Only process match logic if two cards are flipped
                if (gameState.flipped.length === 2) {
                    setTimeout(() => {
                        const idx1 = gameState.flipped[0];
                        const idx2 = gameState.flipped[1];
                        const board = gameState.board || [];
                        if (board[idx1] === board[idx2]) {
                            // Match found
                            gameState.matched = (gameState.matched || []).concat([idx1, idx2]);
                            gameState.scores[gameState.currentPlayerIndex]++;
                            syncToLobby(gameState);
                            setTimeout(() => {
                                gameState.flipped = [];
                                syncToLobby(gameState);
                            }, 700);
                        } else {
                            setTimeout(() => {
                                gameState.flipped = [];
                                gameState.currentPlayerIndex = (gameState.currentPlayerIndex + 1) % (window.playerNames.length || 2);
                                syncToLobby(gameState);
                            }, 700);
                        }
                    }, 700);
                }
            }
        } else {
            $(this).addClass('flipped');
            flippedCards.push($(this));
            if (flippedCards.length === 2) {
                const card1 = flippedCards[0];
                const card2 = flippedCards[1];
                const value1 = card1.attr('data-card');
                const value2 = card2.attr('data-card');
                if (value1 === value2) {
                    scores[currentPlayerIndex]++;
                    if (mode === "solo") {
                        soloMatchedPairs++; 
                    }
                    flippedCards = [];
                    updateScores();
                    checkGameOver();
                } else {
                    setTimeout(() => {
                        card1.removeClass('flipped');
                        card2.removeClass('flipped');
                        if (mode === "solo") {
                            soloMisses++;
                        }
                        currentPlayerIndex = (currentPlayerIndex + 1) % numPlayers;
                        updateTurnIndicator();
                        flippedCards = [];
                        updateScores();
                    }, 1000);
                }
            }
        }
    });

    // --- GAME OVER CHECK ---
    function checkGameOver() {
        let matchedPairs;
        if (isOnline && gameState && gameState.scores) {
            matchedPairs = gameState.scores.reduce((a, b) => a + b, 0);
        } else if (mode === "multi") {
            matchedPairs = scores.reduce((a, b) => a + b, 0);
        } else {
            matchedPairs = soloMatchedPairs;
        }
        if (matchedPairs === totalPairs) {
            let message = "";

            if (isOnline && gameState && gameState.scores) {
                const maxScore = Math.max(...gameState.scores);
                const winners = gameState.scores.map((s, i) => s === maxScore ? i : null).filter(x => x !== null);
                if (winners.length === 1) {
                    const winnerName = window.playerNames?.[winners[0]] || `Player ${winners[0] + 1}`;
                    message = `🏆 ${winnerName} wins!`;
                } else {
                    const names = winners.map(i => window.playerNames?.[i] || `Player ${i + 1}`);
                    message = `🤝 It's a draw between: ${names.join(", ")}`;
                }
            } else if (mode === "multi") {
                const maxScore = Math.max(...scores);
                const winners = scores.map((s, i) => s === maxScore ? i : null).filter(x => x !== null);
                if (winners.length === 1) {
                    const winnerName = window.playerNames?.[winners[0]] || `Player ${winners[0] + 1}`;
                    message = `🏆 ${winnerName} wins!`;
                } else {
                    const names = winners.map(i => window.playerNames?.[i] || `Player ${i + 1}`);
                    message = `🤝 It's a draw between: ${names.join(", ")}`;
                }
            } else {
                // get the solo player’s name from the url, fallback to "solo player" if not found
                message = `You win! Total misses: ${soloMisses}`;
                const soloPlayerName = urlParams.getAll("username[]")[0] || "Solo Player";
                // send a post request to save the score data to the server
                fetch('/Memory/memory/save_score.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: soloPlayerName,
                        misses: soloMisses,
                        pairs: soloMatchedPairs
                    })
                })
                    .then(res => res.json())
                    .then(data => console.log('Score saved:', data))
                    .catch(err => console.error('Error saving score:', err));
            }

            $('#game-over-message').text(message).fadeIn();
            $('#restart-button').fadeIn();
            $('.card').off('click');
        }
    }

    // --- RESTART BUTTON ---
    $('#restart-button').on('click', function () {
        location.reload();
    });
});

// --- PLAYER FORM LOGIC (unchanged) ---
document.addEventListener('DOMContentLoaded', function () {
    const modeRadios = document.querySelectorAll('input[name="mode"]');
    const playerSelect = document.getElementById('player-select');
    if (!playerSelect) return;

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

// fades the footer out after 10 seconds to make more space for the grid
function fadeOutFooter(afterMs = 10000) {
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

function difficultyName(val) {
    if (val == 3 || val == "3") return "Easy";
    if (val == 6 || val == "6") return "Medium";
    if (val == 10 || val == "10") return "Hard";
    return val;
}