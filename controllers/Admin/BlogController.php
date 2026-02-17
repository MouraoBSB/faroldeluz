<?php
/**
 * Controller Admin Blog
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-16 23:31:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/BlogPost.php';
require_once BASE_PATH . '/models/TaxonomyTerm.php';

class AdminBlogController extends Controller {
    private $blogModel;
    private $taxonomyModel;
    
    public function __construct() {
        $this->blogModel = new BlogPost();
        $this->taxonomyModel = new TaxonomyTerm();
    }
    
    public function index() {
        $this->requireAuth();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
        
        if ($search) {
            $result = $this->blogModel->searchPublished($search, $page, 10);
            $posts = $result['data'];
            $pagination = [
                'current_page' => $result['current_page'],
                'total_pages' => $result['last_page'],
                'per_page' => $result['per_page'],
                'total_items' => $result['total']
            ];
        } else {
            $posts = $this->blogModel->all('created_at DESC');
            
            $perPage = 10;
            $offset = ($page - 1) * $perPage;
            $posts = array_slice($posts, $offset, $perPage);
            
            $totalCount = count($this->blogModel->all());
            $totalPages = ceil($totalCount / $perPage);
            
            $pagination = [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_items' => $totalCount
            ];
        }
        
        $this->view('admin/blog/index', [
            'posts' => $posts,
            'pagination' => $pagination,
            'search' => $search
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        $categories = $this->taxonomyModel->getCategories();
        $tags = $this->taxonomyModel->getTags();
        
        $this->view('admin/blog/create', [
            'categories' => $categories,
            'tags' => $tags
        ]);
    }
    
    public function store() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url('admin/blog/criar'));
            return;
        }
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token de segurança inválido';
            $this->redirect(base_url('admin/blog/criar'));
            return;
        }
        
        $title = sanitize_input($_POST['title'] ?? '');
        $excerpt = sanitize_input($_POST['excerpt'] ?? '');
        $content_html = $_POST['content_html'] ?? '';
        $published_at = $_POST['published_at'] ?? date('Y-m-d H:i:s');
        $status = $_POST['status'] ?? 'draft';
        $seo_title = sanitize_input($_POST['seo_title'] ?? '');
        $seo_meta_description = sanitize_input($_POST['seo_meta_description'] ?? '');
        $seo_keywords = sanitize_input($_POST['seo_keywords'] ?? '');
        
        $featured_image_url = '';
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $featured_image_url = $this->uploadFile($_FILES['featured_image'], 'blog');
        }
        
        $slug = $this->generateSlug($title);
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content_html' => $content_html,
            'featured_image_url' => $featured_image_url,
            'published_at' => $published_at,
            'status' => $status,
            'seo_title' => $seo_title ?: $title,
            'seo_meta_description' => $seo_meta_description ?: $excerpt,
            'seo_keywords' => $seo_keywords
        ];
        
        $id = $this->blogModel->create($data);
        
        if ($id) {
            // Salvar categorias e tags
            if (!empty($_POST['categories'])) {
                $this->blogModel->attachTaxonomies($id, $_POST['categories']);
            }
            if (!empty($_POST['tags'])) {
                $this->blogModel->attachTaxonomies($id, $_POST['tags']);
            }
            
            $_SESSION['success'] = 'Post criado com sucesso!';
            $this->redirect(base_url('admin/blog'));
        } else {
            $_SESSION['error'] = 'Erro ao criar post';
            $this->redirect(base_url('admin/blog/criar'));
        }
    }
    
    public function edit($id) {
        $this->requireAuth();
        
        $post = $this->blogModel->find($id);
        
        if (!$post) {
            $_SESSION['error'] = 'Post não encontrado';
            $this->redirect(base_url('admin/blog'));
            return;
        }
        
        $categories = $this->taxonomyModel->getCategories();
        $tags = $this->taxonomyModel->getTags();
        $postTaxonomies = $this->blogModel->getPostTaxonomies($id);
        
        $this->view('admin/blog/edit', [
            'post' => $post,
            'categories' => $categories,
            'tags' => $tags,
            'postTaxonomies' => $postTaxonomies
        ]);
    }
    
    public function update($id) {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url("admin/blog/editar/{$id}"));
            return;
        }
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token de segurança inválido';
            $this->redirect(base_url("admin/blog/editar/{$id}"));
            return;
        }
        
        $post = $this->blogModel->find($id);
        
        if (!$post) {
            $_SESSION['error'] = 'Post não encontrado';
            $this->redirect(base_url('admin/blog'));
            return;
        }
        
        $title = sanitize_input($_POST['title'] ?? '');
        $excerpt = sanitize_input($_POST['excerpt'] ?? '');
        $content_html = $_POST['content_html'] ?? '';
        $published_at = $_POST['published_at'] ?? date('Y-m-d H:i:s');
        $status = $_POST['status'] ?? 'draft';
        $seo_title = sanitize_input($_POST['seo_title'] ?? '');
        $seo_meta_description = sanitize_input($_POST['seo_meta_description'] ?? '');
        $seo_keywords = sanitize_input($_POST['seo_keywords'] ?? '');
        
        $featured_image_url = $post['featured_image_url'];
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            if ($featured_image_url) {
                $this->deleteFile($featured_image_url);
            }
            $featured_image_url = $this->uploadFile($_FILES['featured_image'], 'blog');
        }
        
        $slug = $post['slug'];
        if ($title !== $post['title']) {
            $slug = $this->generateSlug($title);
        }
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content_html' => $content_html,
            'featured_image_url' => $featured_image_url,
            'published_at' => $published_at,
            'status' => $status,
            'seo_title' => $seo_title ?: $title,
            'seo_meta_description' => $seo_meta_description ?: $excerpt,
            'seo_keywords' => $seo_keywords
        ];
        
        $success = $this->blogModel->update($id, $data);
        
        if ($success) {
            // Atualizar categorias e tags
            $this->blogModel->detachAllTaxonomies($id);
            
            if (!empty($_POST['categories'])) {
                $this->blogModel->attachTaxonomies($id, $_POST['categories']);
            }
            if (!empty($_POST['tags'])) {
                $this->blogModel->attachTaxonomies($id, $_POST['tags']);
            }
            
            $_SESSION['success'] = 'Post atualizado com sucesso!';
            $this->redirect(base_url('admin/blog'));
        } else {
            $_SESSION['error'] = 'Erro ao atualizar post';
            $this->redirect(base_url("admin/blog/editar/{$id}"));
        }
    }
    
    public function delete($id) {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url('admin/blog'));
            return;
        }
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token de segurança inválido';
            $this->redirect(base_url('admin/blog'));
            return;
        }
        
        $post = $this->blogModel->find($id);
        
        if ($post) {
            if ($post['featured_image_url']) {
                $this->deleteFile($post['featured_image_url']);
            }
            
            $this->blogModel->delete($id);
            $_SESSION['success'] = 'Post excluído com sucesso!';
        } else {
            $_SESSION['error'] = 'Post não encontrado';
        }
        
        $this->redirect(base_url('admin/blog'));
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
        
        while ($this->blogModel->findBySlug($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}
