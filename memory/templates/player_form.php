<div class="container-box">
    <form action="username.php" method="get" id="setup-form">
        <!-- lets the user choose connection type -->
        <!-- lets the user choose connection type -->
        <label>Connection Type:</label><br>
        <button class="buttonconnection" type="button" data-value="local">Local</button>
        <button class="buttonconnection" type="button" data-value="online">Online</button>
        <input type="hidden" name="connection" id="connection-input">
        <br><br>


        <!-- lets the user choose game mode -->
        <label>Mode:</label><br>
        <input type="radio" name="mode" value="solo" checked> Solo<br>
        <input type="radio" name="mode" value="multi"> Multiplayer<br><br>

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

    const connectionButtons = document.querySelectorAll('.buttonconnection');
    const connectionInput = document.getElementById('connection-input');

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

    modeRadios.forEach(radio => {
        radio.addEventListener('change', updateMode);
    });

    playerButtons.forEach(button => {
        button.addEventListener('click', () => {
            playersInput.value = button.dataset.value;
            playerButtons.forEach(btn => btn.classList.remove('selected'));
            button.classList.add('selected');
        });
    });

    connectionButtons.forEach(button => {
        button.addEventListener('click', () => {
            connectionInput.value = button.dataset.value;
            connectionButtons.forEach(btn => btn.classList.remove('selected'));
            button.classList.add('selected');
        });
    });

    window.addEventListener('DOMContentLoaded', () => {
        updateMode();

        const defaultConnection = document.querySelector('.buttonconnection[data-value="local"]');
        if (defaultConnection) {
            defaultConnection.click(); 
        }
    });
</script>
