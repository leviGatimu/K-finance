<?php
// STRICT WARNING: Delete this file before going to production!
declare(strict_types=1);

// --- 1. LIGHTWEIGHT .ENV PARSER ---
// This loads variables so getenv() works without needing Composer right now.
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue; // Skip comments
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

// Include our Database class
require_once __DIR__ . '/../src/config/database.php';

// Helper function to render UI cards
function renderResult($name, $status, $message, $rawResponse = '')
{
    $color = $status ? '#00E676' : '#FF4D4D'; // K-Finance Success/Danger colors
    $icon = $status ? '✅ SUCCESS' : '❌ FAILED';
    echo "<div style='background: #121212; color: white; padding: 20px; margin-bottom: 15px; border-left: 5px solid $color; border-radius: 5px; font-family: sans-serif;'>";
    echo "<h3 style='margin: 0 0 10px 0; color: $color;'>$icon - $name</h3>";
    echo "<p style='margin: 0 0 10px 0;'><strong>Message:</strong> $message</p>";
    if ($rawResponse) {
        echo "<pre style='background: #222; padding: 10px; border-radius: 4px; overflow-x: auto; font-size: 12px; color: #aaa; margin: 0;'>$rawResponse</pre>";
    }
    echo "</div>";
}

echo "<div style='background: #000; min-height: 100vh; padding: 40px;'>";
echo "<h1 style='color: white; font-family: sans-serif; text-align: center; margin-bottom: 30px;'>K-Finance: System Diagnostic</h1>";

// --- TEST 1: DATABASE CONNECTION ---
try {
    $db = Database::connect();
    renderResult("MySQL Database", true, "Successfully connected to " . getenv('DB_NAME'));
} catch (Exception $e) {
    renderResult("MySQL Database", false, "Database connection error.", $e->getMessage());
}

// --- TEST 2: AFRICA'S TALKING API (SANDBOX) ---
$atUser = getenv('AT_USERNAME');
$atKey = getenv('AT_API_KEY');

if (!$atUser || !$atKey) {
    renderResult("Africa's Talking API", false, "Missing AT_USERNAME or AT_API_KEY in .env file.");
} else {
    // We use a dummy Rwandan number (+250) for the sandbox test
    $postData = http_build_query([
        'username' => $atUser,
        'to' => '+250780000000',
        'message' => 'K-Finance Sandbox Test OTP: 123456'
    ]);

    $ch = curl_init('https://api.sandbox.africastalking.com/version1/messaging');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apiKey: ' . $atKey,
        'Accept: application/json',
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 201 || $httpCode === 200) {
        renderResult("Africa's Talking API", true, "Connected and message queued in Sandbox.", $response);
    } else {
        renderResult("Africa's Talking API", false, "HTTP Code: $httpCode. Check your Sandbox API Key.", $response);
    }
}

// --- TEST 3: GEMINI AI API ---
$geminiKey = getenv('GEMINI_API_KEY');

if (!$geminiKey) {
    renderResult("Gemini AI API", false, "Missing GEMINI_API_KEY in .env file.");
} else {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $geminiKey;

    // A simple math prompt to test the AI's logic engine
    $payload = json_encode([
        "contents" => [
            ["parts" => [["text" => "Reply with exactly one word: 'Connected' if you receive this."]]]
        ]
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        // Decode JSON to show the exact text from the AI
        $data = json_decode($response, true);
        $aiText = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No text found';
        renderResult("Gemini AI API", true, "Connected. AI Responded: " . trim($aiText));
    } else {
        renderResult("Gemini AI API", false, "HTTP Code: $httpCode. Check your API Key.", $response);
    }
}

echo "</div>";
?>