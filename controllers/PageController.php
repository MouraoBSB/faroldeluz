<?php
/**
 * PageController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/Setting.php';

class PageController extends Controller {
    private $settingModel;
    
    public function __construct() {
        $this->settingModel = new Setting();
    }
    
    public function about() {
        $settings = $this->settingModel->getAll();
        $this->view('pages/about', ['settings' => $settings]);
    }
    
    public function batuira() {
        $settings = $this->settingModel->getAll();
        $this->view('pages/batuira', ['settings' => $settings]);
    }
}
