<?php
// Handles saving solo mode score

header("Content-Type: application/json");

$raw = file_get_contents("php://input");

$data = json_decode($raw, true);

// check if required fields are present and valid
if (
    !is_array($data) ||
    !isset($data['name']) ||
    !isset($data['misses'])
) {
    http_response_code(400);
    echo json_encode(["error" => "missing required fields"]);
    exit;
}

// create a new entry with current timestamp
$newEntry = [
    "name" => $data['name'],
    "misses" => (int)$data['misses'],
    "pairs" => (int)($data['pairs'] ?? 0), 
    "timestamp" => date('c') 
];

// define the path to the score file
$file = __DIR__ . "/data/solo_score.json";

// load existing scores if the file exists
$existing = [];
if (file_exists($file)) {
    $content = file_get_contents($file);
    $existing = json_decode($content, true);
    if (!is_array($existing)) {
        $existing = [];
    }
}

// add the new score to the list
$existing[] = $newEntry;

// write the updated list back to the file
$success = file_put_contents($file, json_encode($existing, JSON_PRETTY_PRINT));

// return error if writing failed
if ($success === false) {
    http_response_code(500);
    echo json_encode(["error" => "failed to write file"]);
    exit;
}

// return success message
http_response_code(200);
echo json_encode(["message" => "score saved successfully"]);
