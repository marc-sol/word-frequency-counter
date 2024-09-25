<?php

$stopWords = [
    'i', 'me', 'my', 'we', 'you', 'he', 'she', 'it', 'they', 
    'and', 'the', 'a', 'an', 'is', 'are', 'was', 'were', 
    'of', 'to', 'in', 'for', 'on', 'with', 'that', 'this', 
    'by', 'at', 'but', 'if', 'or', 'not', 'so', 'as'
];

function processFile($filename, $stopWords) {
    if (!file_exists($filename)) {
        return "Error: File not found.";
    }
    
    $text = strtolower(file_get_contents($filename));
    $words = preg_split('/\W+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    
    $frequencies = [];
    foreach ($words as $word) {
        if (!in_array($word, $stopWords)) {
            $frequencies[$word] = ($frequencies[$word] ?? 0) + 1;
        }
    }
    
    arsort($frequencies); 
    return $frequencies;
}

$result = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = $_POST['filename'] ?? '';
    $result = processFile($filename, $stopWords);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Frequency Counter</title>
</head>
<body>
    <h1>Word Frequency Counter</h1>
    <form method="POST">
        <label for="filename">Text File Path:</label>
        <br>
        <input type="text" id="filename" name="filename" required>
        <br><br>
        <input type="submit" value="Calculate Frequencies">
    </form>

    <?php if (!empty($result)): ?>
        <h2>Word Frequencies:</h2>
        <ul>
            <?php foreach ($result as $word => $frequency): ?>
                <li><?= htmlspecialchars($word) ?>: <?= $frequency ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>