<?php
// public/api/verify_otp.php
session_start(); // Start the session so we can log the user in
header('Content-Type: application/json');

// 1. Lightweight .env parser
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
$phone = trim($input['phone'] ?? '');
$otp = trim($input['otp'] ?? '');

if (empty($phone) || empty($otp)) {
    echo json_encode(['success' => false, 'message' => 'Missing phone or OTP.']);
    exit;
}

// Clean up spaces from the phone input
$phone = str_replace(' ', '', $phone);

// Format the phone number exactly like we did in send_otp.php
if (strpos($phone, '+250') !== 0) {
    if (strpos($phone, '07') === 0) {
        $phone = '+250' . substr($phone, 1);
    } else {
        $phone = '+250' . $phone;
    }
}

try {
    $db = Database::connect();

    // 3. Find the user in the database
    $stmt = $db->prepare("SELECT id, full_name, current_otp, otp_expires_at FROM users WHERE phone_number = :phone LIMIT 1");
    $stmt->execute([':phone' => $phone]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // 4. Check if OTP matches
        if ($user['current_otp'] === $otp) {

            // 5. Check if OTP is expired
            if (strtotime($user['otp_expires_at']) > time()) {

                // SUCCESS! 
                // Clear the OTP from the database so it can't be reused
                $update = $db->prepare("UPDATE users SET current_otp = NULL, otp_expires_at = NULL WHERE id = :id");
                $update->execute([':id' => $user['id']]);

                // Create the secure session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];

                echo json_encode(['success' => true, 'message' => 'Verification successful.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'OTP has expired. Please request a new one.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid OTP code. Please check and try again.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User account not found.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>