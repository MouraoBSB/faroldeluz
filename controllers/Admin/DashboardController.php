<?php
/**
 * Admin DashboardController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/Magazine.php';
require_once BASE_PATH . '/models/Dialogo.php';
require_once BASE_PATH . '/models/RajianStudy.php';
require_once BASE_PATH . '/models/BlogPost.php';
require_once BASE_PATH . '/models/NewsletterSubscriber.php';

class AdminDashboardController extends Controller {
    public function index() {
        $this->requireAuth();
        
        $magazineModel = new Magazine();
        $dialogoModel = new Dialogo();
        $rajianModel = new RajianStudy();
        $blogModel = new BlogPost();
        $newsletterModel = new NewsletterSubscriber();
        
        $stats = [
            'magazines_total' => count($magazineModel->all()),
            'dialogos_total' => count($dialogoModel->all()),
            'rajian_total' => count($rajianModel->all()),
            'blog_total' => count($blogModel->all()),
            'newsletter_total' => count($newsletterModel->getActive())
        ];
        
        $this->view('admin/dashboard', ['stats' => $stats]);
    }
}
