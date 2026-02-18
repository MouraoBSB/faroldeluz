<?php
/**
 * Script de Envio para Google Drive
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17
 */

function sendToGoogleDrive($filePath, $type = 'database') {
    require_once __DIR__ . '/../config/config.php';
    
    try {
        $db = new PDO(
            "mysql:host=" . DB_CONFIG['host'] . ";port=" . DB_CONFIG['port'] . ";dbname=" . DB_CONFIG['database'] . ";charset=" . DB_CONFIG['charset'],
            DB_CONFIG['username'],
            DB_CONFIG['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        $stmt = $db->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'backup_gdrive_%'");
        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
        $clientId = $settings['backup_gdrive_client_id'] ?? '';
        $clientSecret = $settings['backup_gdrive_client_secret'] ?? '';
        $refreshToken = $settings['backup_gdrive_refresh_token'] ?? '';
        $folderId = $settings['backup_gdrive_folder_id'] ?? '';
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => 'Erro ao conectar ao banco: ' . $e->getMessage()
        ];
    }
    
    if (empty($clientId) || empty($clientSecret) || empty($refreshToken)) {
        return [
            'success' => false,
            'error' => 'Credenciais do Google Drive não configuradas'
        ];
    }
    
    if (!file_exists($filePath)) {
        return [
            'success' => false,
            'error' => 'Arquivo não encontrado: ' . $filePath
        ];
    }
    
    $accessToken = getAccessToken($clientId, $clientSecret, $refreshToken);
    
    if (!$accessToken) {
        return [
            'success' => false,
            'error' => 'Erro ao obter access token do Google'
        ];
    }
    
    $fileName = basename($filePath);
    $mimeType = 'application/gzip';
    
    $metadata = [
        'name' => $fileName,
        'mimeType' => $mimeType
    ];
    
    if ($folderId) {
        $metadata['parents'] = [$folderId];
    }
    
    $boundary = uniqid();
    $delimiter = "\r\n--{$boundary}\r\n";
    $closeDelimiter = "\r\n--{$boundary}--";
    
    $multipartBody = $delimiter;
    $multipartBody .= "Content-Type: application/json; charset=UTF-8\r\n\r\n";
    $multipartBody .= json_encode($metadata);
    $multipartBody .= $delimiter;
    $multipartBody .= "Content-Type: {$mimeType}\r\n";
    $multipartBody .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $multipartBody .= base64_encode(file_get_contents($filePath));
    $multipartBody .= $closeDelimiter;
    
    $ch = curl_init('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $multipartBody);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$accessToken}",
        "Content-Type: multipart/related; boundary={$boundary}",
        "Content-Length: " . strlen($multipartBody)
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode >= 200 && $httpCode < 300) {
        $result = json_decode($response, true);
        return [
            'success' => true,
            'file_id' => $result['id'] ?? null,
            'file_name' => $fileName
        ];
    } else {
        return [
            'success' => false,
            'error' => "HTTP {$httpCode}: {$response}"
        ];
    }
}

function getAccessToken($clientId, $clientSecret, $refreshToken) {
    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'refresh_token' => $refreshToken,
        'grant_type' => 'refresh_token'
    ]));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        return $data['access_token'] ?? null;
    }
    
    return null;
}
