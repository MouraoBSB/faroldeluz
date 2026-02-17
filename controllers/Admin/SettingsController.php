<?php
/**
 * Admin SettingsController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 17:39:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/Setting.php';

class AdminSettingsController extends Controller {
    private $settingModel;
    
    public function __construct() {
        $this->settingModel = new Setting();
    }
    
    public function index() {
        $this->requireAuth();
        
        $settings = $this->settingModel->getAll();
        
        $this->view('admin/settings/index', [
            'settings' => $settings
        ]);
    }
    
    public function update() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url('admin/configuracoes'));
            return;
        }
        
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Token de segurança inválido';
            $this->redirect(base_url('admin/configuracoes'));
            return;
        }
        
        $uploadDir = BASE_PATH . '/assets/uploads/settings/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        if (isset($_FILES['revista_imagem_destaque']) && $_FILES['revista_imagem_destaque']['error'] === UPLOAD_ERR_OK) {
            $fileExtension = strtolower(pathinfo($_FILES['revista_imagem_destaque']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $currentImage = $this->settingModel->get('revista_imagem_destaque');
                if ($currentImage && file_exists(BASE_PATH . '/' . $currentImage)) {
                    unlink(BASE_PATH . '/' . $currentImage);
                }
                
                $fileName = 'revista_destaque_' . uniqid() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['revista_imagem_destaque']['tmp_name'], $filePath)) {
                    $this->settingModel->set('revista_imagem_destaque', 'assets/uploads/settings/' . $fileName);
                }
            }
        }
        
        if (isset($_FILES['dialogos_imagem_destaque']) && $_FILES['dialogos_imagem_destaque']['error'] === UPLOAD_ERR_OK) {
            $fileExtension = strtolower(pathinfo($_FILES['dialogos_imagem_destaque']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $currentImage = $this->settingModel->get('dialogos_imagem_destaque');
                if ($currentImage && file_exists(BASE_PATH . '/' . $currentImage)) {
                    unlink(BASE_PATH . '/' . $currentImage);
                }
                
                $fileName = 'dialogos_destaque_' . uniqid() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['dialogos_imagem_destaque']['tmp_name'], $filePath)) {
                    $this->settingModel->set('dialogos_imagem_destaque', 'assets/uploads/settings/' . $fileName);
                }
            }
        }
        
        if (isset($_FILES['rajian_imagem_destaque']) && $_FILES['rajian_imagem_destaque']['error'] === UPLOAD_ERR_OK) {
            $fileExtension = strtolower(pathinfo($_FILES['rajian_imagem_destaque']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $currentImage = $this->settingModel->get('rajian_imagem_destaque');
                if ($currentImage && file_exists(BASE_PATH . '/' . $currentImage)) {
                    unlink(BASE_PATH . '/' . $currentImage);
                }
                
                $fileName = 'rajian_destaque_' . uniqid() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['rajian_imagem_destaque']['tmp_name'], $filePath)) {
                    $this->settingModel->set('rajian_imagem_destaque', 'assets/uploads/settings/' . $fileName);
                }
            }
        }
        
        if (isset($_FILES['blog_imagem_destaque']) && $_FILES['blog_imagem_destaque']['error'] === UPLOAD_ERR_OK) {
            $fileExtension = strtolower(pathinfo($_FILES['blog_imagem_destaque']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $currentImage = $this->settingModel->get('blog_imagem_destaque');
                if ($currentImage && file_exists(BASE_PATH . '/' . $currentImage)) {
                    unlink(BASE_PATH . '/' . $currentImage);
                }
                
                $fileName = 'blog_destaque_' . uniqid() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['blog_imagem_destaque']['tmp_name'], $filePath)) {
                    $this->settingModel->set('blog_imagem_destaque', 'assets/uploads/settings/' . $fileName);
                }
            }
        }
        
        if (isset($_FILES['sobre_imagem']) && $_FILES['sobre_imagem']['error'] === UPLOAD_ERR_OK) {
            $fileExtension = strtolower(pathinfo($_FILES['sobre_imagem']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $currentImage = $this->settingModel->get('sobre_imagem');
                if ($currentImage && file_exists(BASE_PATH . '/' . $currentImage)) {
                    unlink(BASE_PATH . '/' . $currentImage);
                }
                
                $fileName = 'sobre_' . uniqid() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['sobre_imagem']['tmp_name'], $filePath)) {
                    $this->settingModel->set('sobre_imagem', 'assets/uploads/settings/' . $fileName);
                }
            }
        }
        
        if (isset($_FILES['batuira_imagem']) && $_FILES['batuira_imagem']['error'] === UPLOAD_ERR_OK) {
            $fileExtension = strtolower(pathinfo($_FILES['batuira_imagem']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $currentImage = $this->settingModel->get('batuira_imagem');
                if ($currentImage && file_exists(BASE_PATH . '/' . $currentImage)) {
                    unlink(BASE_PATH . '/' . $currentImage);
                }
                
                $fileName = 'batuira_' . uniqid() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['batuira_imagem']['tmp_name'], $filePath)) {
                    $this->settingModel->set('batuira_imagem', 'assets/uploads/settings/' . $fileName);
                }
            }
        }
        
        if (isset($_FILES['site_favicon']) && $_FILES['site_favicon']['error'] === UPLOAD_ERR_OK) {
            $fileExtension = strtolower(pathinfo($_FILES['site_favicon']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['ico', 'png', 'svg'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $currentFavicon = $this->settingModel->get('site_favicon');
                if ($currentFavicon && file_exists(BASE_PATH . '/' . $currentFavicon)) {
                    unlink(BASE_PATH . '/' . $currentFavicon);
                }
                
                $fileName = 'favicon_' . uniqid() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['site_favicon']['tmp_name'], $filePath)) {
                    $this->settingModel->set('site_favicon', 'assets/uploads/settings/' . $fileName);
                }
            }
        }
        
        if (isset($_FILES['site_og_image']) && $_FILES['site_og_image']['error'] === UPLOAD_ERR_OK) {
            $fileExtension = strtolower(pathinfo($_FILES['site_og_image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $currentImage = $this->settingModel->get('site_og_image');
                if ($currentImage && file_exists(BASE_PATH . '/' . $currentImage)) {
                    unlink(BASE_PATH . '/' . $currentImage);
                }
                
                $fileName = 'og_image_' . uniqid() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['site_og_image']['tmp_name'], $filePath)) {
                    $this->settingModel->set('site_og_image', 'assets/uploads/settings/' . $fileName);
                }
            }
        }
        
        $settingsToUpdate = [
            'whatsapp_url',
            'whatsapp_group_url',
            'whatsapp_channel_url',
            'contact_email',
            'facebook_url',
            'instagram_url',
            'youtube_url',
            'revista_titulo',
            'revista_descricao',
            'revista_texto_adicional',
            'dialogos_titulo',
            'dialogos_descricao',
            'dialogos_texto_adicional',
            'rajian_titulo',
            'rajian_descricao',
            'rajian_texto_adicional',
            'blog_titulo',
            'blog_descricao',
            'blog_texto_adicional',
            'sobre_titulo',
            'sobre_texto',
            'batuira_titulo',
            'batuira_texto',
            'site_og_title',
            'site_meta_description',
            'rajian_whatsapp_group_url'
        ];
        
        foreach ($settingsToUpdate as $key) {
            if (isset($_POST[$key])) {
                $value = $_POST[$key];
                if ($key !== 'revista_descricao' && $key !== 'revista_texto_adicional' && 
                    $key !== 'dialogos_descricao' && $key !== 'dialogos_texto_adicional' &&
                    $key !== 'rajian_descricao' && $key !== 'rajian_texto_adicional' &&
                    $key !== 'blog_descricao' && $key !== 'blog_texto_adicional' &&
                    $key !== 'sobre_texto' && $key !== 'batuira_texto' &&
                    $key !== 'site_meta_description') {
                    $value = sanitize_input($value);
                }
                $this->settingModel->set($key, $value);
            }
        }
        
        $_SESSION['success'] = 'Configurações atualizadas com sucesso!';
        $this->redirect(base_url('admin/configuracoes'));
    }
}
