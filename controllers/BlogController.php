<?php
/**
 * BlogController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-16 23:35:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/BlogPost.php';
require_once BASE_PATH . '/models/Setting.php';
require_once BASE_PATH . '/models/TaxonomyTerm.php';

class BlogController extends Controller {
    private $blogModel;
    private $settingModel;
    private $taxonomyModel;
    
    public function __construct() {
        $this->blogModel = new BlogPost();
        $this->settingModel = new Setting();
        $this->taxonomyModel = new TaxonomyTerm();
    }
    
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
        $categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : null;
        
        if ($categoryFilter) {
            $result = $this->blogModel->getPostsByTaxonomy($categoryFilter, $page, 12);
        } elseif ($search) {
            $result = $this->blogModel->searchPublished($search, $page, 12);
        } else {
            $result = $this->blogModel->paginatePublished($page, 12);
        }
        
        // Carregar taxonomias para cada post
        foreach ($result['data'] as &$post) {
            $post['taxonomies'] = $this->blogModel->getPostTaxonomies($post['id']);
        }
        
        $categories = $this->taxonomyModel->getCategories();
        
        $blogSettings = [
            'blog_titulo' => $this->settingModel->get('blog_titulo'),
            'blog_descricao' => $this->settingModel->get('blog_descricao'),
            'blog_imagem_destaque' => $this->settingModel->get('blog_imagem_destaque'),
            'blog_texto_adicional' => $this->settingModel->get('blog_texto_adicional')
        ];
        
        $this->view('blog/index', [
            'posts' => $result['data'],
            'pagination' => $result,
            'search' => $search,
            'categories' => $categories,
            'categoryFilter' => $categoryFilter,
            'blogSettings' => $blogSettings
        ]);
    }
    
    public function show($slug) {
        $post = $this->blogModel->findBySlug($slug);
        
        if (!$post || $post['status'] !== 'published') {
            http_response_code(404);
            require_once BASE_PATH . '/views/errors/404.php';
            return;
        }
        
        $taxonomies = $this->blogModel->getPostTaxonomies($post['id']);
        
        $this->view('blog/single', [
            'post' => $post,
            'taxonomies' => $taxonomies
        ]);
    }
}
