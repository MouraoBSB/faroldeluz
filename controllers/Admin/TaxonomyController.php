<?php
/**
 * Admin TaxonomyController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17 01:16:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/TaxonomyTerm.php';

class AdminTaxonomyController extends Controller {
    private $taxonomyModel;
    
    public function __construct() {
        $this->taxonomyModel = new TaxonomyTerm();
    }
    
    public function index() {
        $this->requireAuth();
        
        $type = isset($_GET['type']) ? sanitize_input($_GET['type']) : 'category';
        $search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
        
        if ($search) {
            $terms = $this->taxonomyModel->searchByType($type, $search);
        } else {
            $terms = $this->taxonomyModel->getByType($type);
        }
        
        $this->view('admin/taxonomy/index', [
            'terms' => $terms,
            'type' => $type,
            'search' => $search
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        $this->view('admin/taxonomy/create');
    }
    
    public function store() {
        $this->requireAuth();
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token CSRF inválido';
            $this->redirect(base_url('admin/taxonomias'));
            return;
        }
        
        $data = [
            'name' => sanitize_input($_POST['name']),
            'slug' => $this->generateSlug($_POST['name']),
            'taxonomy_type' => sanitize_input($_POST['type']),
            'description' => $_POST['description'] ?? ''
        ];
        
        $this->taxonomyModel->create($data);
        
        $_SESSION['success'] = ucfirst($_POST['type']) . ' criada com sucesso!';
        $this->redirect(base_url('admin/taxonomias?type=' . $_POST['type']));
    }
    
    public function edit($id) {
        $this->requireAuth();
        
        $term = $this->taxonomyModel->find($id);
        
        if (!$term) {
            $_SESSION['error'] = 'Termo não encontrado';
            $this->redirect(base_url('admin/taxonomias'));
            return;
        }
        
        $this->view('admin/taxonomy/edit', ['term' => $term]);
    }
    
    public function update($id) {
        $this->requireAuth();
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token CSRF inválido';
            $this->redirect(base_url('admin/taxonomias'));
            return;
        }
        
        $data = [
            'name' => sanitize_input($_POST['name']),
            'slug' => $this->generateSlug($_POST['name']),
            'description' => $_POST['description'] ?? ''
        ];
        
        $this->taxonomyModel->update($id, $data);
        
        $term = $this->taxonomyModel->find($id);
        $_SESSION['success'] = ucfirst($term['taxonomy_type']) . ' atualizada com sucesso!';
        $this->redirect(base_url('admin/taxonomias?type=' . $term['taxonomy_type']));
    }
    
    public function delete($id) {
        $this->requireAuth();
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token CSRF inválido';
            $this->redirect(base_url('admin/taxonomias'));
            return;
        }
        
        $term = $this->taxonomyModel->find($id);
        $type = $term['taxonomy_type'];
        
        $this->taxonomyModel->delete($id);
        
        $_SESSION['success'] = ucfirst($type) . ' deletada com sucesso!';
        $this->redirect(base_url('admin/taxonomias?type=' . $type));
    }
    
    private function generateSlug($text) {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        $text = trim($text, '-');
        return $text;
    }
}
