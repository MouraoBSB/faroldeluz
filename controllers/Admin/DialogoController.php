<?php
/**
 * Admin DialogoController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-16 22:50:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/Dialogo.php';

class AdminDialogoController extends Controller {
    private $dialogoModel;
    
    public function __construct() {
        $this->dialogoModel = new Dialogo();
    }
    
    public function index() {
        $this->requireAuth();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
        
        if ($search) {
            $result = $this->dialogoModel->search($search, $page, 20);
        } else {
            $result = $this->dialogoModel->paginate($page, 20);
        }
        
        $this->view('admin/dialogos/index', [
            'dialogos' => $result['data'] ?? [],
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
        $this->view('admin/dialogos/create');
    }
    
    public function store() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url('admin/dialogos'));
            return;
        }
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token de segurança inválido';
            $this->redirect(base_url('admin/dialogos/criar'));
            return;
        }
        
        $title = sanitize_input($_POST['title'] ?? '');
        $description = $_POST['description'] ?? '';
        $youtube_url = sanitize_input($_POST['youtube_url'] ?? '');
        $published_at = sanitize_input($_POST['published_date'] ?? date('Y-m-d')) . ' 00:00:00';
        $status = sanitize_input($_POST['status'] ?? 'draft');
        $seo_title = sanitize_input($_POST['meta_title'] ?? '');
        $seo_meta_description = sanitize_input($_POST['meta_description'] ?? '');
        
        if (empty($title)) {
            $_SESSION['error'] = 'O título é obrigatório';
            $this->redirect(base_url('admin/dialogos/criar'));
            return;
        }
        
        if (empty($youtube_url)) {
            $_SESSION['error'] = 'A URL do YouTube é obrigatória';
            $this->redirect(base_url('admin/dialogos/criar'));
            return;
        }
        
        $coverUrl = '';
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $coverUrl = $this->uploadFile($_FILES['cover_image'], 'dialogos');
            if (!$coverUrl) {
                $_SESSION['error'] = 'Erro ao fazer upload da imagem de capa';
                $this->redirect(base_url('admin/dialogos/criar'));
                return;
            }
        }
        
        $slug = $this->generateSlug($title);
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'description_html' => $description,
            'youtube_url' => $youtube_url,
            'cover_image_url' => $coverUrl,
            'published_at' => $published_at,
            'status' => $status,
            'seo_title' => $seo_title ?: $title,
            'seo_meta_description' => $seo_meta_description
        ];
        
        $id = $this->dialogoModel->create($data);
        
        if ($id) {
            $_SESSION['success'] = 'Diálogo criado com sucesso!';
            $this->redirect(base_url('admin/dialogos'));
        } else {
            $_SESSION['error'] = 'Erro ao criar diálogo';
            $this->redirect(base_url('admin/dialogos/criar'));
        }
    }
    
    public function edit($id) {
        $this->requireAuth();
        
        $dialogo = $this->dialogoModel->find($id);
        
        if (!$dialogo) {
            $_SESSION['error'] = 'Diálogo não encontrado';
            $this->redirect(base_url('admin/dialogos'));
            return;
        }
        
        $this->view('admin/dialogos/edit', ['dialogo' => $dialogo]);
    }
    
    public function update($id) {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url('admin/dialogos'));
            return;
        }
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token de segurança inválido';
            $this->redirect(base_url("admin/dialogos/editar/{$id}"));
            return;
        }
        
        $dialogo = $this->dialogoModel->find($id);
        
        if (!$dialogo) {
            $_SESSION['error'] = 'Diálogo não encontrado';
            $this->redirect(base_url('admin/dialogos'));
            return;
        }
        
        $title = sanitize_input($_POST['title'] ?? '');
        $description = $_POST['description'] ?? '';
        $youtube_url = sanitize_input($_POST['youtube_url'] ?? '');
        $published_at = sanitize_input($_POST['published_date'] ?? date('Y-m-d')) . ' 00:00:00';
        $status = sanitize_input($_POST['status'] ?? 'draft');
        $seo_title = sanitize_input($_POST['meta_title'] ?? '');
        $seo_meta_description = sanitize_input($_POST['meta_description'] ?? '');
        
        if (empty($title)) {
            $_SESSION['error'] = 'O título é obrigatório';
            $this->redirect(base_url("admin/dialogos/editar/{$id}"));
            return;
        }
        
        if (empty($youtube_url)) {
            $_SESSION['error'] = 'A URL do YouTube é obrigatória';
            $this->redirect(base_url("admin/dialogos/editar/{$id}"));
            return;
        }
        
        $coverUrl = $dialogo['cover_image_url'];
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            if ($coverUrl && file_exists(BASE_PATH . '/' . $coverUrl)) {
                unlink(BASE_PATH . '/' . $coverUrl);
            }
            
            $coverUrl = $this->uploadFile($_FILES['cover_image'], 'dialogos');
            if (!$coverUrl) {
                $_SESSION['error'] = 'Erro ao fazer upload da imagem de capa';
                $this->redirect(base_url("admin/dialogos/editar/{$id}"));
                return;
            }
        }
        
        $slug = $this->generateSlug($title);
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'description_html' => $description,
            'youtube_url' => $youtube_url,
            'cover_image_url' => $coverUrl,
            'published_at' => $published_at,
            'status' => $status,
            'seo_title' => $seo_title ?: $title,
            'seo_meta_description' => $seo_meta_description
        ];
        
        $updated = $this->dialogoModel->update($id, $data);
        
        if ($updated) {
            $_SESSION['success'] = 'Diálogo atualizado com sucesso!';
            $this->redirect(base_url('admin/dialogos'));
        } else {
            $_SESSION['error'] = 'Erro ao atualizar diálogo';
            $this->redirect(base_url("admin/dialogos/editar/{$id}"));
        }
    }
    
    public function delete($id) {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url('admin/dialogos'));
            return;
        }
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token de segurança inválido';
            $this->redirect(base_url('admin/dialogos'));
            return;
        }
        
        $dialogo = $this->dialogoModel->find($id);
        
        if (!$dialogo) {
            $_SESSION['error'] = 'Diálogo não encontrado';
            $this->redirect(base_url('admin/dialogos'));
            return;
        }
        
        if ($dialogo['cover_image_url'] && file_exists(BASE_PATH . '/' . $dialogo['cover_image_url'])) {
            unlink(BASE_PATH . '/' . $dialogo['cover_image_url']);
        }
        
        $deleted = $this->dialogoModel->delete($id);
        
        if ($deleted) {
            $_SESSION['success'] = 'Diálogo excluído com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao excluir diálogo';
        }
        
        $this->redirect(base_url('admin/dialogos'));
    }
    
    private function uploadFile($file, $folder) {
        $uploadDir = BASE_PATH . '/assets/uploads/' . $folder . '/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        
        if (!in_array($file['type'], $allowedTypes)) {
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
    
    private function generateSlug($title) {
        $slug = strtolower($title);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->dialogoModel->findBySlug($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}
