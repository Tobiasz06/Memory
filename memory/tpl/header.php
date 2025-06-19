<!-- Makes banner uptop including the back to menu button, and opens the css files -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Memory Game</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div>
    <header>
        <h1>Memory Match</h1>
    </header>
    <?php if (!empty($showTopControls)): ?>
        <div class="top-controls">
            <button onclick="window.location.href='index.php'">◄ Back to menu</button>
        </div>
    <?php endif; ?>
