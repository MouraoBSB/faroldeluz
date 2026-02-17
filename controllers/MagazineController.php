<?php
/**
 * MagazineController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 17:30:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/Magazine.php';
require_once BASE_PATH . '/models/Setting.php';

class MagazineController extends Controller {
    public function index() {
        $magazineModel = new Magazine();
        $settingModel = new Setting();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
        $order = isset($_GET['order']) ? sanitize_input($_GET['order']) : 'recent';
        
        $orderBy = match($order) {
            'oldest' => 'published_at ASC',
            'title' => 'title ASC',
            default => 'published_at DESC'
        };
        
        if ($search) {
            $result = $magazineModel->searchPublished($search, $page, 12, $orderBy);
        } else {
            $result = $magazineModel->paginatePublished($page, 12, $orderBy);
        }
        
        $magazineSettings = [
            'revista_titulo' => $settingModel->get('revista_titulo'),
            'revista_descricao' => $settingModel->get('revista_descricao'),
            'revista_texto_adicional' => $settingModel->get('revista_texto_adicional'),
            'revista_imagem_destaque' => $settingModel->get('revista_imagem_destaque')
        ];
        
        $this->view('magazines/index', [
            'magazines' => $result['data'] ?? [],
            'pagination' => [
                'total' => $result['total'] ?? 0,
                'per_page' => $result['per_page'] ?? 12,
                'current_page' => $result['current_page'] ?? 1,
                'total_pages' => $result['last_page'] ?? 1
            ],
            'search' => $search,
            'order' => $order,
            'magazineSettings' => $magazineSettings
        ]);
    }
    
    public function show($slug) {
        $magazineModel = new Magazine();
        $magazine = $magazineModel->findBySlug($slug);
        
        if (!$magazine || $magazine['status'] !== 'published') {
            http_response_code(404);
            require_once BASE_PATH . '/views/errors/404.php';
            return;
        }
        
        $this->view('magazines/show', [
            'magazine' => $magazine
        ]);
    }
    
    public function latest() {
        $magazineModel = new Magazine();
        $magazine = $magazineModel->getLatest();
        
        $this->json([
            'magazine' => $magazine
        ]);
    }
}
