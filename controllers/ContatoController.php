<?php
/**
 * ContatoController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17 02:13:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/Setting.php';

class ContatoController extends Controller {
    private $settingModel;
    
    public function __construct() {
        $this->settingModel = new Setting();
    }
    
    public function index() {
        $contato = [
            'whatsapp' => $this->settingModel->get('whatsapp_url'),
            'whatsapp_grupo' => $this->settingModel->get('whatsapp_group_url'),
            'whatsapp_canal' => $this->settingModel->get('whatsapp_channel_url'),
            'email' => $this->settingModel->get('contact_email'),
            'facebook' => $this->settingModel->get('facebook_url'),
            'instagram' => $this->settingModel->get('instagram_url'),
            'youtube' => $this->settingModel->get('youtube_url')
        ];
        
        $this->view('contato/index', [
            'contato' => $contato
        ]);
    }
}
