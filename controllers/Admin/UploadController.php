<?php
/**
 * Controller Admin Upload
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-16 23:35:00
 */

require_once BASE_PATH . '/core/Controller.php';

class AdminUploadController extends Controller {
    
    public function uploadImage() {
        $this->requireAuth();
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if (!isset($_FILES['file'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Nenhum arquivo enviado']);
                exit;
            }
            
            $file = $_FILES['file'];
            
            if ($file['error'] !== UPLOAD_ERR_OK) {
                http_response_code(400);
                echo json_encode(['error' => 'Erro no upload: ' . $file['error']]);
                exit;
            }
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (!in_array($extension, $allowedExtensions)) {
                http_response_code(400);
                echo json_encode(['error' => 'Tipo de arquivo não permitido. Use: ' . implode(', ', $allowedExtensions)]);
                exit;
            }
            
            $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
            
            if (!in_array($file['type'], $allowedMimeTypes)) {
                http_response_code(400);
                echo json_encode(['error' => 'MIME type não permitido: ' . $file['type']]);
                exit;
            }
            
            $uploadDir = BASE_PATH . '/assets/uploads/blog/content/';
            
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    http_response_code(500);
                    echo json_encode(['error' => 'Erro ao criar diretório de upload']);
                    exit;
                }
            }
            
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $filepath = $uploadDir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $location = base_url('assets/uploads/blog/content/' . $filename);
                echo json_encode(['location' => $location]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Erro ao mover arquivo']);
                exit;
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Exceção: ' . $e->getMessage()]);
            exit;
        }
    }
}
