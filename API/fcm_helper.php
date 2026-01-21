<?php
/**
 * Firebase Cloud Messaging Helper
 * Sends push notifications via FCM HTTP v1 API
 */

// FCM Configuration - You need to download your service account key from Firebase Console
// Go to: Firebase Console > Project Settings > Service Accounts > Generate New Private Key
define('FCM_SERVICE_ACCOUNT_PATH', __DIR__ . '/../config/firebase-service-account.json');
define('FCM_PROJECT_ID', 'lifeline-notification');

/**
 * Get OAuth2 access token for FCM
 */
function getFCMAccessToken() {
    $serviceAccountPath = FCM_SERVICE_ACCOUNT_PATH;
    
    if (!file_exists($serviceAccountPath)) {
        error_log('FCM: Service account file not found at ' . $serviceAccountPath);
        return null;
    }
    
    $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
    
    // Create JWT
    $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
    
    $now = time();
    $payload = base64_encode(json_encode([
        'iss' => $serviceAccount['client_email'],
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud' => 'https://oauth2.googleapis.com/token',
        'iat' => $now,
        'exp' => $now + 3600
    ]));
    
    $signature = '';
    $privateKey = openssl_pkey_get_private($serviceAccount['private_key']);
    openssl_sign("$header.$payload", $signature, $privateKey, OPENSSL_ALGO_SHA256);
    $signature = base64_encode($signature);
    
    // URL-safe base64
    $jwt = str_replace(['+', '/', '='], ['-', '_', ''], "$header.$payload.$signature");
    
    // Exchange JWT for access token
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://oauth2.googleapis.com/token',
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    return $data['access_token'] ?? null;
}

/**
 * Send FCM notification to a single token
 */
function sendFCMNotification($token, $title, $body, $data = []) {
    $accessToken = getFCMAccessToken();
    
    if (!$accessToken) {
        error_log('FCM: Failed to get access token');
        return false;
    }
    
    $message = [
        'message' => [
            'token' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body
            ],
            'webpush' => [
                'notification' => [
                    'icon' => '/GSS%20home/res/lifeline.png',
                    'badge' => '/GSS%20home/res/lifeline.png',
                    'vibrate' => [200, 100, 200],
                    'requireInteraction' => true
                ],
                'fcm_options' => [
                    'link' => '/GSS%20home/portal/index.php'
                ]
            ],
            'data' => array_map('strval', $data)
        ]
    ];
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://fcm.googleapis.com/v1/projects/' . FCM_PROJECT_ID . '/messages:send',
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($message),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ]
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        error_log('FCM Error: ' . $response);
        return false;
    }
    
    return true;
}

/**
 * Send FCM notification to all registered tokens
 */
function sendFCMToAll($title, $body, $data = []) {
    try {
        $db = getDB();
        $stmt = $db->query("SELECT token FROM fcm_tokens WHERE active = 1");
        $tokens = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $successCount = 0;
        $failedTokens = [];
        
        foreach ($tokens as $token) {
            if (sendFCMNotification($token, $title, $body, $data)) {
                $successCount++;
            } else {
                $failedTokens[] = $token;
            }
        }
        
        // Optionally deactivate failed tokens
        if (!empty($failedTokens)) {
            $placeholders = implode(',', array_fill(0, count($failedTokens), '?'));
            $deactivateStmt = $db->prepare("UPDATE fcm_tokens SET active = 0 WHERE token IN ($placeholders)");
            $deactivateStmt->execute($failedTokens);
        }
        
        return [
            'success' => $successCount,
            'failed' => count($failedTokens),
            'total' => count($tokens)
        ];
        
    } catch (Exception $e) {
        error_log('FCM sendToAll error: ' . $e->getMessage());
        return ['success' => 0, 'failed' => 0, 'total' => 0];
    }
}

/**
 * Send FCM notification to a topic (all subscribed devices)
 */
function sendFCMToTopic($topic, $title, $body, $data = []) {
    $accessToken = getFCMAccessToken();
    
    if (!$accessToken) {
        error_log('FCM: Failed to get access token');
        return false;
    }
    
    $message = [
        'message' => [
            'topic' => $topic,
            'notification' => [
                'title' => $title,
                'body' => $body
            ],
            'webpush' => [
                'notification' => [
                    'icon' => '/GSS%20home/res/lifeline.png',
                    'badge' => '/GSS%20home/res/lifeline.png',
                    'vibrate' => [200, 100, 200],
                    'requireInteraction' => true
                ],
                'fcm_options' => [
                    'link' => '/GSS%20home/portal/index.php'
                ]
            ],
            'data' => array_map('strval', $data)
        ]
    ];
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://fcm.googleapis.com/v1/projects/' . FCM_PROJECT_ID . '/messages:send',
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($message),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ]
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        error_log('FCM Topic Error: ' . $response);
        return false;
    }
    
    return true;
}
?>
