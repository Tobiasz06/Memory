<div class="container-box">
    <form action="game.php" method="get" id="setup-form">
        <label>Mode:</label><br>
        <input type="radio" name="mode" value="solo" checked> Solo<br>
        <input type="radio" name="mode" value="multi"> Multiplayer<br><br>

        <div id="player-select">
            <label>Number of Players (if multiplayer):</label>
            <select name="players">
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select><br><br>
        </div>

        <label>Select Difficulty</label><br>
        <button class="button buttoneasy" type="submit" name="pairs" value="3">Easy</button>
        <button class="button buttonmedium" type="submit" name="pairs" value="6">Medium</button>
        <button class="button buttonhard" type="submit" name="pairs" value="10">Hard</button>
    </form>
</div>
