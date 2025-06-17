<?php include 'tpl/header.php'; ?>
<div class="center-vertical-wrapper">
    <div class="container-box" id="online-lobby-app">
        <h2>Online Multiplayer</h2>
        <div id="lobby-create">
            <h3>Create Lobby</h3>
            <input type="text" id="host-username" placeholder="Your username" required><br>
            <input type="text" id="lobby-name" placeholder="Lobby name" required><br>
            <input type="password" id="lobby-password" placeholder="Lobby password" required><br>
            <label>Difficulty:</label>
            <select id="lobby-difficulty">
                <option value="3">Easy</option>
                <option value="6" selected>Medium</option>
                <option value="10">Hard</option>
            </select><br>
            <label>Players:</label>
            <select id="lobby-players">
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select><br>
            <button id="create-lobby-btn">Create Lobby</button>
        </div>
        <hr>
        <div id="lobby-list-section">
            <h3>Available Lobbies</h3>
            <div id="lobby-list"></div>
        </div>
        <div id="lobby-join" style="display:none;">
            <h3>Join Lobby</h3>
            <div id="join-lobby-info"></div>
            <input type="text" id="join-username" placeholder="Your username" required><br>
            <input type="password" id="join-password" placeholder="Lobby password" required><br>
            <button id="join-lobby-btn">Join Lobby</button>
            <button id="cancel-join-btn">Cancel</button>
            <div id="join-lobby-status"></div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>