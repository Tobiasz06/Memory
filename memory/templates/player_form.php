<div class="container-box">
    <form action="game.php" method="get" id="setup-form">
        <!-- lets the user choose game mode -->
        <label>Mode:</label><br>
        <input type="radio" name="mode" value="solo" checked> Solo<br>
        <input type="radio" name="mode" value="multi"> Multiplayer<br><br>

        <!-- Solo player name -->
        <div id="solo-name-input">
            <label>Your name:</label>
            <input type="text" name="player1" placeholder="Your name">
        </div>

        <!-- Multiplayer names -->
        <div id="multi-names" style="display: none;">
            <label>Player Names:</label><br>
            <div id="dynamic-name-fields"></div>
        </div>

        <!-- this block appears only in multiplayer to choose number of players -->
        <div id="player-select" style="display: none;">
            <label>Number of Players:</label><br>
            <button class="buttonplayers" type="button" data-value="2">2</button>
            <button class="buttonplayers" type="button" data-value="3">3</button>
            <button class="buttonplayers" type="button" data-value="4">4</button>
            <input type="hidden" name="players" id="players-input">
            <br><br>
        </div>

        <!-- lets the user pick difficulty level -->
        <label>Select Difficulty:</label><br>
        <button class="button buttoneasy" type="submit" name="pairs" value="3">Easy</button>
        <button class="button buttonmedium" type="submit" name="pairs" value="6">Medium</button>
        <button class="button buttonhard" type="submit" name="pairs" value="10">Hard</button>
    </form>
</div>

<script>
    const modeRadios = document.querySelectorAll('input[name="mode"]');
    const playerSelect = document.getElementById('player-select');
    const playerButtons = document.querySelectorAll('.buttonplayers');
    const playersInput = document.getElementById('players-input');

    // this function shows or hides the player number selector
    function updateMode() {
        const selectedMode = document.querySelector('input[name="mode"]:checked').value;
        if (selectedMode === 'multi') {
            playerSelect.style.display = 'block';
        } else {
            playerSelect.style.display = 'none';
            playersInput.value = '';
            playerButtons.forEach(btn => btn.classList.remove('selected'));
        }
    }

    //  update view when radio button changes
    modeRadios.forEach(radio => {
        radio.addEventListener('change', updateMode);
    });

    function updatePlayerInputs() {
        const players = document.getElementById('players-input').value || 2;
        const container = document.getElementById('dynamic-name-fields');
        container.innerHTML = '';
        for (let i = 1; i <= players; i++) {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = `player${i}`;
            input.placeholder = `Player ${i} name`;
            container.appendChild(input);
            container.appendChild(document.createElement('br'));
        }
    }

    function toggleNameInputs() {
        const mode = document.querySelector('input[name="mode"]:checked').value;
        document.getElementById('solo-name-input').style.display = (mode === 'solo') ? 'block' : 'none';
        document.getElementById('multi-names').style.display = (mode === 'multi') ? 'block' : 'none';
        updatePlayerInputs();
    }

    modeRadios.forEach(r => r.addEventListener('change', toggleNameInputs));
    playerButtons.forEach(button => button.addEventListener('click', updatePlayerInputs));
    toggleNameInputs();

    // save selected number of players and highlights the button
    playerButtons.forEach(button => {
        button.addEventListener('click', () => {
            playersInput.value = button.dataset.value;

            playerButtons.forEach(btn => btn.classList.remove('selected'));
            button.classList.add('selected');
        });
    });

    // run at start to apply correct mode
    updateMode();
</script>
