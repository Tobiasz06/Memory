<form action="game.php" method="get" id="setup-form">
    <label>Mode:</label><br>
    <input type="radio" name="mode" value="solo" checked> Solo<br>
    <input type="radio" name="mode" value="multi"> Multiplayer<br><br>

    <label>Number of Players (if multiplayer):</label>
    <select name="players">
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
    </select><br><br>

    <label>Card Pairs:</label>
    <select name="pairs">
        <?php for ($i = 2; $i <= 10; $i++): ?>
            <option value="<?= $i ?>"><?= $i ?></option>
        <?php endfor; ?>
    </select><br><br>

    <button type="submit">Start Game</button>
</form>
