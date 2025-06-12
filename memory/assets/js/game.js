document.addEventListener('DOMContentLoaded', function () {
    const modeRadios = document.querySelectorAll('input[name="mode"]');
    const playerSelect = document.getElementById('player-select');

    function togglePlayerSelect() {
        const mode = document.querySelector('input[name="mode"]:checked').value;
        playerSelect.style.display = (mode === 'Multiplayer') ? 'block' : 'none';
    }

    // On load
    togglePlayerSelect();

    // On change
    modeRadios.forEach(radio => {
        radio.addEventListener('change', togglePlayerSelect);
    });
});

