<?php
/**
 * Admin MagazineController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 17:25:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/Magazine.php';

class AdminMagazineController extends Controller {
    private $magazineModel;
    
    public function __construct() {
        $this->magazineModel = new Magazine();
    }
    
    public function index() {
        $this->requireAuth();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
        
        if ($search) {
            $result = $this->magazineModel->search($search, $page, 20);
        } else {
            $result = $this->magazineModel->paginate($page, 20);
        }
        
        $this->view('admin/magazines/index', [
            'magazines' => $result['data'] ?? [],
            'pagination' => [
                'total' => $result['total'] ?? 0,
                'per_page' => $result['per_page'] ?? 20,
                'current_page' => $result['current_page'] ?? 1,
                'total_pages' => $result['last_page'] ?? 1
            ],
            'search' => $search
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        $this->view('admin/magazines/create');
    }
    
    public function store() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url('admin/revistas'));
            return;
        }
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token de segurança inválido';
            $this->redirect(base_url('admin/revistas/criar'));
            return;
        }
        
        $title = sanitize_input($_POST['title'] ?? '');
        $description = $_POST['description'] ?? '';
        $pdf_url = sanitize_input($_POST['pdf_url'] ?? '');
        $published_at = sanitize_input($_POST['published_date'] ?? date('Y-m-d')) . ' 00:00:00';
        $status = sanitize_input($_POST['status'] ?? 'draft');
        $seo_title = sanitize_input($_POST['meta_title'] ?? '');
        $seo_meta_description = sanitize_input($_POST['meta_description'] ?? '');
        
        if (empty($title)) {
            $_SESSION['error'] = 'O título é obrigatório';
            $this->redirect(base_url('admin/revistas/criar'));
            return;
        }
        
        $coverUrl = '';
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $coverUrl = $this->uploadFile($_FILES['cover_image'], 'covers');
            if (!$coverUrl) {
                $_SESSION['error'] = 'Erro ao fazer upload da imagem de capa';
                $this->redirect(base_url('admin/revistas/criar'));
                return;
            }
        }
        
        $slug = generate_slug($title);
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'cover_image_url' => $coverUrl,
            'pdf_url' => $pdf_url,
            'description_html' => $description,
            'published_at' => $published_at,
            'status' => $status,
            'seo_title' => $seo_title ?: $title,
            'seo_meta_description' => $seo_meta_description
        ];
        
        $id = $this->magazineModel->create($data);
        
        if ($id) {
            $_SESSION['success'] = 'Revista criada com sucesso!';
            $this->redirect(base_url('admin/revistas'));
        } else {
            $_SESSION['error'] = 'Erro ao criar revista';
            $this->redirect(base_url('admin/revistas/criar'));
        }
    }
    
    public function edit($id) {
        $this->requireAuth();
        
        $magazine = $this->magazineModel->find($id);
        
        if (!$magazine) {
            $_SESSION['error'] = 'Revista não encontrada';
            $this->redirect(base_url('admin/revistas'));
            return;
        }
        
        $this->view('admin/magazines/edit', ['magazine' => $magazine]);
    }
    
    public function update($id) {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url('admin/revistas'));
            return;
        }
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token de segurança inválido';
            $this->redirect(base_url("admin/revistas/{$id}/editar"));
            return;
        }
        
        $magazine = $this->magazineModel->find($id);
        
        if (!$magazine) {
            $_SESSION['error'] = 'Revista não encontrada';
            $this->redirect(base_url('admin/revistas'));
            return;
        }
        
        $title = sanitize_input($_POST['title'] ?? '');
        $description = $_POST['description'] ?? '';
        $pdf_url = sanitize_input($_POST['pdf_url'] ?? '');
        $published_at = sanitize_input($_POST['published_date'] ?? date('Y-m-d')) . ' 00:00:00';
        $status = sanitize_input($_POST['status'] ?? 'draft');
        $seo_title = sanitize_input($_POST['meta_title'] ?? '');
        $seo_meta_description = sanitize_input($_POST['meta_description'] ?? '');
        
        if (empty($title)) {
            $_SESSION['error'] = 'O título é obrigatório';
            $this->redirect(base_url("admin/revistas/{$id}/editar"));
            return;
        }
        
        $coverUrl = $magazine['cover_image_url'];
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $newCover = $this->uploadFile($_FILES['cover_image'], 'covers');
            if ($newCover) {
                if ($coverUrl) {
                    $this->deleteFile($coverUrl);
                }
                $coverUrl = $newCover;
            }
        }
        
        $slug = generate_slug($title);
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'cover_image_url' => $coverUrl,
            'pdf_url' => $pdf_url,
            'description_html' => $description,
            'published_at' => $published_at,
            'status' => $status,
            'seo_title' => $seo_title ?: $title,
            'seo_meta_description' => $seo_meta_description
        ];
        
        $success = $this->magazineModel->update($id, $data);
        
        if ($success) {
            $_SESSION['success'] = 'Revista atualizada com sucesso!';
            $this->redirect(base_url('admin/revistas'));
        } else {
            $_SESSION['error'] = 'Erro ao atualizar revista';
            $this->redirect(base_url("admin/revistas/{$id}/editar"));
        }
    }
    
    public function delete($id) {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url('admin/revistas'));
            return;
        }
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token de segurança inválido';
            $this->redirect(base_url('admin/revistas'));
            return;
        }
        
        $magazine = $this->magazineModel->find($id);
        
        if ($magazine) {
            if ($magazine['cover_image_url']) {
                $this->deleteFile($magazine['cover_image_url']);
            }
            
            $this->magazineModel->delete($id);
            $_SESSION['success'] = 'Revista deletada com sucesso!';
        } else {
            $_SESSION['error'] = 'Revista não encontrada';
        }
        
        $this->redirect(base_url('admin/revistas'));
    }
    
    private function uploadFile($file, $folder) {
        $uploadDir = BASE_PATH . '/assets/uploads/' . $folder . '/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $allowedTypes = [
            'covers' => ['image/jpeg', 'image/jpg', 'image/png', 'image/webp']
        ];
        
        if (!in_array($file['type'], $allowedTypes[$folder])) {
            return false;
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return '/assets/uploads/' . $folder . '/' . $filename;
        }
        
        return false;
    }
    
    private function deleteFile($url) {
        $filepath = BASE_PATH . $url;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
}
