<?php
/**
 * LifeLine Device Create API
 * Creates a new device in the mesh network
 */

require_once '../../database.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, null, 'Method not allowed', 405);
}

// Get input data
$input = getJSONInput();

// Validate required fields
$missing = validateRequired($input, ['LID']);
if (!empty($missing)) {
    sendResponse(false, null, 'Missing required fields: ' . implode(', ', $missing), 400);
}

// Extract and sanitize data
$deviceId = isset($input['DID']) ? (int) $input['DID'] : null;
$deviceName = isset($input['device_name']) ? trim($input['device_name']) : null;
$lid = (int) $input['LID'];
$status = isset($input['status']) ? $input['status'] : 'active';

// Validate status
$validStatuses = ['active', 'inactive', 'maintenance'];
if (!in_array($status, $validStatuses)) {
    sendResponse(false, null, 'Invalid status. Must be one of: ' . implode(', ', $validStatuses), 400);
}

try {
    $db = getDB();

    // Check if DID already exists (if specified)
    if ($deviceId !== null) {
        $checkStmt = $db->prepare("SELECT DID FROM devices WHERE DID = :did");
        $checkStmt->execute(['did' => $deviceId]);
        if ($checkStmt->fetch()) {
            sendResponse(false, null, 'Device ID ' . $deviceId . ' already exists', 409);
        }
    }

    // Insert new device (with or without specified DID)
    if ($deviceId !== null) {
        $stmt = $db->prepare("
            INSERT INTO devices (DID, device_name, LID, status, last_ping) 
            VALUES (:did, :device_name, :lid, :status, NOW())
        ");
        $stmt->execute([
            'did' => $deviceId,
            'device_name' => $deviceName,
            'lid' => $lid,
            'status' => $status
        ]);
    } else {
        $stmt = $db->prepare("
            INSERT INTO devices (device_name, LID, status, last_ping) 
            VALUES (:device_name, :lid, :status, NOW())
        ");
        $stmt->execute([
            'device_name' => $deviceName,
            'lid' => $lid,
            'status' => $status
        ]);
    }

    $newDeviceId = $deviceId !== null ? $deviceId : $db->lastInsertId();

    // Fetch created device
    $fetchStmt = $db->prepare("SELECT * FROM devices WHERE DID = :did");
    $fetchStmt->execute(['did' => $newDeviceId]);
    $device = $fetchStmt->fetch();

    sendResponse(true, $device, 'Device created successfully', 201);

} catch (PDOException $e) {
    error_log('Device create error: ' . $e->getMessage());
    sendResponse(false, null, 'Database error: ' . $e->getMessage(), 500);
}
?>