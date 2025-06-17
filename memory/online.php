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
<script>
function refreshLobbyList() {
    $.get('assets/ajax/list_lobbies.php', function(lobbies) {
        let html = '<table><tr><th>Name</th><th>Players</th><th>Difficulty</th><th>Join</th></tr>';
        lobbies.forEach(lobby => {
            html += `<tr>
                <td>${lobby.name}</td>
                <td>${lobby.players.length}/${lobby.maxPlayers}</td>
                <td>${lobby.difficulty}</td>
                <td><button class="join-lobby" data-id="${lobby.id}">Join</button></td>
            </tr>`;
        });
        html += '</table>';
        $('#lobby-list').html(html);
    }, 'json');
}
setInterval(refreshLobbyList, 2000);
refreshLobbyList();

$(document).on('click', '.join-lobby', function() {
    const lobbyId = $(this).data('id');
    $('#lobby-join').show();
    $('#lobby-create').hide();
    $('#lobby-list-section').hide();
    $('#join-lobby-info').text('Lobby: ' + lobbyId);
    $('#join-lobby-btn').data('id', lobbyId);
});
$('#cancel-join-btn').on('click', function() {
    $('#lobby-join').hide();
    $('#lobby-create').show();
    $('#lobby-list-section').show();
    $('#join-lobby-status').text('');
});
$('#create-lobby-btn').on('click', function() {
    const data = {
        name: $('#lobby-name').val(),
        password: $('#lobby-password').val(),
        hostUsername: $('#host-username').val(),
        difficulty: $('#lobby-difficulty').val(),
        maxPlayers: $('#lobby-players').val()
    };
    if (!data.name || !data.password || !data.hostUsername) {
        alert('Fill in all fields!');
        return;
    }
    $.post('assets/ajax/create_lobby.php', data, function(res) {
        if (res.success) {
            window.location.href = `game.php?lobby=${res.lobbyId}&player=0`;
        } else {
            alert(res.message || 'Failed to create lobby.');
        }
    }, 'json');
});
$('#join-lobby-btn').on('click', function() {
    const lobbyId = $(this).data('id');
    const data = {
        lobbyId: lobbyId,
        password: $('#join-password').val(),
        username: $('#join-username').val()
    };
    if (!data.password || !data.username) {
        $('#join-lobby-status').text('Fill in all fields!');
        return;
    }
    $.post('assets/ajax/join_lobby.php', data, function(res) {
        if (res.success) {
            window.location.href = `game.php?lobby=${lobbyId}&player=${res.playerIndex}`;
        } else {
            $('#join-lobby-status').text(res.message || 'Failed to join lobby.');
        }
    }, 'json');
});
</script>
<?php include 'tpl/footer.php'; ?>