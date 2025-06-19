<!-- Manages all online parts, creating, joining, displaying and starting lobbies -->

<?php include 'tpl/header.php'; ?>
<body class="online-page">
<div class="top-controls">
    <button onclick="window.location.href='index.php'">◄ Back to menu</button>
</div>
<div class="center-vertical-wrapper">
    <div class="container-box waiting-lobby" id="online-lobby-app">
        <h2>Online Multiplayer</h2>
        <div id="lobby-create">
            <h3>Create Lobby</h3>
            <!-- Create Lobby -->
            <input type="text" id="host-username" placeholder="Your username" required class="username-input host-username"><br>
            <input type="text" id="lobby-name" placeholder="Lobby name" required class="username-input"><br>
            <input type="password" id="lobby-password" placeholder="Lobby password" required class="username-input"><br>
            <label>Select Difficulty:</label><br>
            <button type="button" class="button buttoneasy" data-value="3">Easy</button>
            <button type="button" class="button buttonmedium selected" data-value="6">Medium</button>
            <button type="button" class="button buttonhard" data-value="10">Hard</button>
            <input type="hidden" id="lobby-difficulty" value="6"><br>
            <label>Players:</label><br>
            <button type="button" class="buttonplayers selected" data-value="2">2</button>
            <button type="button" class="buttonplayers" data-value="3">3</button>
            <button type="button" class="buttonplayers" data-value="4">4</button>
            <input type="hidden" id="lobby-players" value="2"><br>
            <button id="create-lobby-btn" class="start-game-btn">Create Lobby</button>
        </div>
        <hr>
        <div id="lobby-list-section">
            <h3>Available Lobbies</h3>
            <div id="lobby-list"></div>
        </div>
        <div id="lobby-join" style="display:none;">
            <h3>Join Lobby</h3>
            <div id="join-lobby-info"></div>
            <!-- Join Lobby -->
            <input type="text" id="join-username" placeholder="Your username" required class="username-input"><br>
            <input type="password" id="join-password" placeholder="Lobby password" required class="username-input"><br>
            <button id="join-lobby-btn" class="start-game-btn">Join</button>
            <button id="cancel-join-btn" class="buttonplayers">Cancel</button>
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
                <td>${difficultyName(lobby.difficulty)}</td>
                <td><button class="join-lobby start-game-btn" data-id="${lobby.id}">Join</button></td>
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
    $('#join-lobby-btn').data('id', lobbyId);
});
$('#cancel-join-btn').on('click', function() {
    $('#lobby-join').hide();
    $('#lobby-create').show();
    $('#lobby-list-section').show();
    $('#join-lobby-status').text('');
});
$('#create-lobby-btn').on('click', function(e) {
    e.preventDefault();
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

// Difficulty selection
const diffButtons = document.querySelectorAll('.buttoneasy, .buttonmedium, .buttonhard');
const diffInput = document.getElementById('lobby-difficulty');
diffButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        diffButtons.forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        diffInput.value = btn.dataset.value;
    });
});

diffButtons[1].classList.add('selected'); 

// Player count selection
const playerButtons = document.querySelectorAll('.buttonplayers');
const playerInput = document.getElementById('lobby-players');
playerButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        playerButtons.forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        playerInput.value = btn.dataset.value;
    });
});

playerButtons[0].classList.add('selected'); 

function difficultyName(val) {
    if (val == 3 || val == "3") return "Easy";
    if (val == 6 || val == "6") return "Medium";
    if (val == 10 || val == "10") return "Hard";
    return val;
}
</script>
<?php include 'tpl/footer.php'; ?>