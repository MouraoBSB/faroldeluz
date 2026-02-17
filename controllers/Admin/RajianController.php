<?php
/**
 * Controller Admin Rajian
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-16 23:25:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/RajianStudy.php';

class AdminRajianController extends Controller {
    private $rajianModel;
    
    public function __construct() {
        $this->rajianModel = new RajianStudy();
    }
    
    public function index() {
        $this->requireAuth();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $search = $_GET['search'] ?? '';
        
        if ($search) {
            $studies = $this->rajianModel->searchPublished($search, $page, $perPage);
            $rajians = $studies['data'];
            $totalCount = $studies['total'];
            $totalPages = $studies['last_page'];
        } else {
            $rajians = $this->rajianModel->all('created_at DESC');
            $totalCount = count($rajians);
            $totalPages = ceil($totalCount / $perPage);
            
            // Aplicar paginação manual
            $offset = ($page - 1) * $perPage;
            $rajians = array_slice($rajians, $offset, $perPage);
        }
        $this->view('admin/rajian/index', [
            'rajians' => $rajians,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_items' => $totalCount
            ],
            'search' => $search
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        $this->view('admin/rajian/create');
    }
    
    public function store() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url('admin/rajian/criar'));
            return;
        }
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token de segurança inválido';
            $this->redirect(base_url('admin/rajian/criar'));
            return;
        }
        
        $title = sanitize_input($_POST['title'] ?? '');
        $description_html = $_POST['description_html'] ?? '';
        $youtube_url = sanitize_input($_POST['youtube_url'] ?? '');
        $published_at = $_POST['published_at'] ?? date('Y-m-d H:i:s');
        $status = $_POST['status'] ?? 'draft';
        $seo_title = sanitize_input($_POST['seo_title'] ?? '');
        $seo_meta_description = sanitize_input($_POST['seo_meta_description'] ?? '');
        
        $cover_image_url = '';
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $cover_image_url = $this->uploadFile($_FILES['cover_image'], 'rajian');
        }
        
        $slug = $this->generateSlug($title);
        
        // Extrair video ID do YouTube (suporta vídeos normais e lives)
        $youtube_video_id = '';
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|live\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $youtube_url, $matches)) {
            $youtube_video_id = $matches[1];
        }
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'description_html' => $description_html,
            'youtube_url' => $youtube_url,
            'youtube_video_id' => $youtube_video_id,
            'cover_image_url' => $cover_image_url,
            'published_at' => $published_at,
            'status' => $status,
            'seo_title' => $seo_title ?: $title,
            'seo_meta_description' => $seo_meta_description
        ];
        
        $id = $this->rajianModel->create($data);
        
        if ($id) {
            $_SESSION['success'] = 'Rajian criado com sucesso!';
            $this->redirect(base_url('admin/rajian'));
        } else {
            $_SESSION['error'] = 'Erro ao criar rajian';
            $this->redirect(base_url('admin/rajian/criar'));
        }
    }
    
    public function edit($id) {
        $this->requireAuth();
        
        $rajian = $this->rajianModel->find($id);
        
        if (!$rajian) {
            $_SESSION['error'] = 'Rajian não encontrado';
            $this->redirect(base_url('admin/rajian'));
            return;
        }
        
        $this->view('admin/rajian/edit', ['rajian' => $rajian]);
    }
    
    public function update($id) {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url("admin/rajian/editar/{$id}"));
            return;
        }
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token de segurança inválido';
            $this->redirect(base_url("admin/rajian/editar/{$id}"));
            return;
        }
        
        $rajian = $this->rajianModel->find($id);
        
        if (!$rajian) {
            $_SESSION['error'] = 'Rajian não encontrado';
            $this->redirect(base_url('admin/rajian'));
            return;
        }
        
        $title = sanitize_input($_POST['title'] ?? '');
        $description_html = $_POST['description_html'] ?? '';
        $youtube_url = sanitize_input($_POST['youtube_url'] ?? '');
        $published_at = $_POST['published_at'] ?? date('Y-m-d H:i:s');
        $status = $_POST['status'] ?? 'draft';
        $seo_title = sanitize_input($_POST['seo_title'] ?? '');
        $seo_meta_description = sanitize_input($_POST['seo_meta_description'] ?? '');
        
        $cover_image_url = $rajian['cover_image_url'] ?? '';
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            if ($cover_image_url) {
                $this->deleteFile($cover_image_url);
            }
            $cover_image_url = $this->uploadFile($_FILES['cover_image'], 'rajian');
        }
        
        $slug = $rajian['slug'];
        if ($title !== $rajian['title']) {
            $slug = $this->generateSlug($title);
        }
        
        // Extrair video ID do YouTube (suporta vídeos normais e lives)
        $youtube_video_id = '';
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|live\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $youtube_url, $matches)) {
            $youtube_video_id = $matches[1];
        }
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'description_html' => $description_html,
            'youtube_url' => $youtube_url,
            'youtube_video_id' => $youtube_video_id,
            'cover_image_url' => $cover_image_url,
            'published_at' => $published_at,
            'status' => $status,
            'seo_title' => $seo_title ?: $title,
            'seo_meta_description' => $seo_meta_description
        ];
        
        $success = $this->rajianModel->update($id, $data);
        
        if ($success) {
            $_SESSION['success'] = 'Rajian atualizado com sucesso!';
            $this->redirect(base_url('admin/rajian'));
        } else {
            $_SESSION['error'] = 'Erro ao atualizar rajian';
            $this->redirect(base_url("admin/rajian/editar/{$id}"));
        }
    }
    
    public function delete($id) {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url('admin/rajian'));
            return;
        }
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token de segurança inválido';
            $this->redirect(base_url('admin/rajian'));
            return;
        }
        
        $rajian = $this->rajianModel->find($id);
        
        if ($rajian) {
            if (!empty($rajian['cover_image_url'])) {
                $this->deleteFile($rajian['cover_image_url']);
            }
        }
        
        $deleted = $this->rajianModel->delete($id);
        
        if ($deleted) {
            $_SESSION['success'] = 'Rajian excluído com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao excluir rajian';
        }
        
        $this->redirect(base_url('admin/rajian'));
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
        
        while ($this->rajianModel->findBySlug($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}
