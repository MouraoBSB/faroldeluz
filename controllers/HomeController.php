<?php
/**
 * HomeController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/Magazine.php';
require_once BASE_PATH . '/models/Dialogo.php';
require_once BASE_PATH . '/models/RajianStudy.php';
require_once BASE_PATH . '/models/BlogPost.php';
require_once BASE_PATH . '/models/Setting.php';

class HomeController extends Controller {
    public function index() {
        $magazineModel = new Magazine();
        $dialogoModel = new Dialogo();
        $rajianModel = new RajianStudy();
        $blogModel = new BlogPost();
        $settingModel = new Setting();
        
        $latestMagazine = $magazineModel->getLatest();
        $dialogos = $dialogoModel->getPublished(8);
        $rajianStudies = $rajianModel->getPublished(6);
        $blogPosts = $blogModel->getPublished(6);
        $settings = $settingModel->getAll();
        
        $this->view('home', [
            'latestMagazine' => $latestMagazine,
            'dialogos' => $dialogos,
            'rajianStudies' => $rajianStudies,
            'blogPosts' => $blogPosts,
            'settings' => $settings
        ]);
    }
}
