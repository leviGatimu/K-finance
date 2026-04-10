<?php
// public/api/send_otp.php
header('Content-Type: application/json');

// 1. Lightweight .env parser (from our diagnostic test)
$envPath = __DIR__ . '/../../.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

require_once __DIR__ . '/../../src/config/database.php';

// 2. Read incoming JSON payload
$input = json_decode(file_get_contents('php://input'), true);
$fullName = trim($input['fullName'] ?? '');
$phone = trim($input['phone'] ?? '');

if (empty($phone)) {
    echo json_encode(['success' => false, 'message' => 'Phone number is required.']);
    exit;
}

// Ensure phone number has the Rwandan country code format (+250)
if (strpos($phone, '+250') !== 0) {
    // If it starts with 078, replace 0 with +250
    if (strpos($phone, '07') === 0) {
        $phone = '+250' . substr($phone, 1);
    } else {
        $phone = '+250' . $phone;
    }
}

// 3. Generate a secure 6-digit OTP and set expiration (10 minutes)
$otp = (string) random_int(100000, 999999);
$expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));

try {
    $db = Database::connect();

    // 4. UPSERT: Insert new user or update existing user's OTP
    $stmt = $db->prepare("
        INSERT INTO users (full_name, phone_number, current_otp, otp_expires_at) 
        VALUES (:name, :phone, :otp, :expires)
        ON DUPLICATE KEY UPDATE 
        current_otp = VALUES(current_otp), 
        otp_expires_at = VALUES(otp_expires_at)
    ");

    // If it's a returning user, we might not have the name, so fallback to existing
    $stmt->execute([
        ':name' => $fullName ? $fullName : 'Returning User',
        ':phone' => $phone,
        ':otp' => $otp,
        ':expires' => $expiresAt
    ]);

    // 5. Send SMS via Africa's Talking
    $atUser = getenv('AT_USERNAME');
    $atKey = getenv('AT_API_KEY');

    $message = "Your K-Finance security code is: {$otp}. It expires in 10 minutes.";

    $postData = http_build_query([
        'username' => $atUser,
        'to' => $phone,
        'message' => $message
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
        echo json_encode(['success' => true, 'message' => 'OTP sent successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send SMS gateway request.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>