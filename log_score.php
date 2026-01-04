<?php
// log_score.php — writes username and score to a text file

// Get data
$username = $_POST['user'] ?? 'guest';
$score = (int)($_POST['score'] ?? 0);

// Sanitize username (letters, numbers, underscore, dash only)
$username = preg_replace('/[^a-zA-Z0-9_\-]/', '', $username);
if (empty($username)) $username = 'guest';

// Validate score
if ($score <= 0 || $score > 999999) {
    die("Invalid score");
}

// Log file path
$logFile = 'scores.log';

// Create if not exists
if (!file_exists($logFile)) {
    touch($logFile);
    chmod($logFile, 0666); // Make writable
}

// Append new line: "username: score"
$entry = $username . ': ' . $score . "\n";

// Write safely (with file locking)
$file = fopen($logFile, 'a');
if (flock($file, LOCK_EX)) {
    fwrite($file, $entry);
    flock($file, LOCK_UN);
}
fclose($file);

echo "Logged: $entry";
?>