<?php
/**
 * FCM Token Registration API
 * Registers and manages FCM tokens for push notifications
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../database.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($method === 'POST') {
    // Register new FCM token
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['token']) || empty($input['token'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Token is required']);
        exit;
    }

    $token = $input['token'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $userId = $input['user_id'] ?? null;

    try {
        $db = getDB();

        // Check if token already exists
        $checkStmt = $db->prepare("SELECT id, active FROM fcm_tokens WHERE token = ?");
        $checkStmt->execute([$token]);
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Reactivate if inactive
            if (!$existing['active']) {
                $updateStmt = $db->prepare("UPDATE fcm_tokens SET active = 1, updated_at = NOW() WHERE id = ?");
                $updateStmt->execute([$existing['id']]);
            }
            echo json_encode(['success' => true, 'message' => 'Token already registered']);
        } else {
            // Insert new token
            $insertStmt = $db->prepare("
                INSERT INTO fcm_tokens (token, user_agent, user_id, created_at, updated_at, active) 
                VALUES (?, ?, ?, NOW(), NOW(), 1)
            ");
            $insertStmt->execute([$token, $userAgent, $userId]);

            echo json_encode(['success' => true, 'message' => 'Token registered successfully']);
        }

    } catch (Exception $e) {
        error_log('FCM Register Error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Failed to register token']);
    }

} elseif ($method === 'DELETE') {
    // Unregister FCM token
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['token']) || empty($input['token'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Token is required']);
        exit;
    }

    try {
        $db = getDB();
        $stmt = $db->prepare("UPDATE fcm_tokens SET active = 0, updated_at = NOW() WHERE token = ?");
        $stmt->execute([$input['token']]);

        echo json_encode(['success' => true, 'message' => 'Token unregistered']);

    } catch (Exception $e) {
        error_log('FCM Unregister Error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Failed to unregister token']);
    }

} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>