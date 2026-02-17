<?php
/**
 * DialogosController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/Dialogo.php';

class DialogosController extends Controller {
    public function index() {
        $dialogoModel = new Dialogo();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
        
        if ($search) {
            $result = $dialogoModel->searchPublished($search, $page, 12);
        } else {
            $result = $dialogoModel->paginatePublished($page, 12);
        }
        
        $this->view('dialogos/index', [
            'dialogos' => $result['data'],
            'pagination' => $result,
            'search' => $search
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
        
        $this->view('dialogos/single', [
            'dialogo' => $dialogo
        ]);
    }
}
