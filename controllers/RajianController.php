<?php
/**
 * RajianController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-16 23:25:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/RajianStudy.php';
require_once BASE_PATH . '/models/Setting.php';

class RajianController extends Controller {
    private $rajianModel;
    private $settingModel;
    
    public function __construct() {
        $this->rajianModel = new RajianStudy();
        $this->settingModel = new Setting();
    }
    
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
        $order = isset($_GET['order']) ? sanitize_input($_GET['order']) : 'recent';
        $perPage = 9;
        
        if ($search) {
            $result = $this->rajianModel->searchPublished($search, $page, $perPage);
            $rajians = $result['data'];
            $totalCount = $result['total'];
            $totalPages = $result['last_page'];
        } else {
            $result = $this->rajianModel->paginatePublished($page, $perPage);
            $rajians = $result['data'];
            $totalCount = $result['total'];
            $totalPages = $result['last_page'];
        }
        
        $rajianSettings = [
            'rajian_titulo' => $this->settingModel->get('rajian_titulo'),
            'rajian_descricao' => $this->settingModel->get('rajian_descricao'),
            'rajian_imagem_destaque' => $this->settingModel->get('rajian_imagem_destaque'),
            'rajian_texto_adicional' => $this->settingModel->get('rajian_texto_adicional'),
            'rajian_whatsapp_group_url' => $this->settingModel->get('rajian_whatsapp_group_url')
        ];
        
        $this->view('rajian/index', [
            'rajians' => $rajians,
            'rajianSettings' => $rajianSettings,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_items' => $totalCount
            ],
            'search' => $search,
            'order' => $order
        ]);
    }
    
    public function show($slug) {
        $rajian = $this->rajianModel->findBySlug($slug);
        
        if (!$rajian || $rajian['status'] !== 'published') {
            http_response_code(404);
            require_once BASE_PATH . '/views/errors/404.php';
            return;
        }
        
        $this->view('rajian/show', [
            'rajian' => $rajian
        ]);
    }
}
