<?php
/**
 * DialogoController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-16 22:55:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/Dialogo.php';
require_once BASE_PATH . '/models/Setting.php';

class DialogoController extends Controller {
    public function index() {
        $dialogoModel = new Dialogo();
        $settingModel = new Setting();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
        $order = isset($_GET['order']) ? sanitize_input($_GET['order']) : 'recent';
        
        switch($order) {
            case 'oldest':
                $orderBy = 'published_at ASC';
                break;
            case 'title':
                $orderBy = 'title ASC';
                break;
            default:
                $orderBy = 'published_at DESC';
                break;
        }
        
        if ($search) {
            $result = $dialogoModel->searchPublished($search, $page, 12, $orderBy);
        } else {
            $result = $dialogoModel->paginatePublished($page, 12, $orderBy);
        }
        
        $dialogoSettings = [
            'dialogos_titulo' => $settingModel->get('dialogos_titulo'),
            'dialogos_descricao' => $settingModel->get('dialogos_descricao'),
            'dialogos_texto_adicional' => $settingModel->get('dialogos_texto_adicional'),
            'dialogos_imagem_destaque' => $settingModel->get('dialogos_imagem_destaque')
        ];
        
        $this->view('dialogos/index', [
            'dialogos' => $result['data'] ?? [],
            'pagination' => [
                'total' => $result['total'] ?? 0,
                'per_page' => $result['per_page'] ?? 12,
                'current_page' => $result['current_page'] ?? 1,
                'total_pages' => $result['last_page'] ?? 1
            ],
            'search' => $search,
            'order' => $order,
            'dialogoSettings' => $dialogoSettings
        ]);
    }
    
    public function show($slug) {
        $dialogoModel = new Dialogo();
        $dialogo = $dialogoModel->findBySlug($slug);
        
        if (!$dialogo || $dialogo['status'] !== 'published') {
            http_response_code(404);
            require_once BASE_PATH . '/views/errors/404.php';
            return;
        }
        
        $this->view('dialogos/show', [
            'dialogo' => $dialogo
        ]);
    }
}
