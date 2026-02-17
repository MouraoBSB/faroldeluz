<?php
/**
 * SitemapController
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/models/Magazine.php';
require_once BASE_PATH . '/models/Dialogo.php';
require_once BASE_PATH . '/models/RajianStudy.php';
require_once BASE_PATH . '/models/BlogPost.php';

class SitemapController extends Controller {
    public function index() {
        header('Content-Type: application/xml; charset=utf-8');
        
        $magazineModel = new Magazine();
        $dialogoModel = new Dialogo();
        $rajianModel = new RajianStudy();
        $blogModel = new BlogPost();
        
        $magazines = $magazineModel->getPublished();
        $dialogos = $dialogoModel->getPublished();
        $rajianStudies = $rajianModel->getPublished();
        $blogPosts = $blogModel->getPublished();
        
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        echo '<url><loc>' . base_url() . '</loc><changefreq>daily</changefreq><priority>1.0</priority></url>';
        echo '<url><loc>' . base_url('revista') . '</loc><changefreq>weekly</changefreq><priority>0.9</priority></url>';
        echo '<url><loc>' . base_url('dialogos') . '</loc><changefreq>weekly</changefreq><priority>0.9</priority></url>';
        echo '<url><loc>' . base_url('rajian') . '</loc><changefreq>weekly</changefreq><priority>0.9</priority></url>';
        echo '<url><loc>' . base_url('blog') . '</loc><changefreq>daily</changefreq><priority>0.9</priority></url>';
        echo '<url><loc>' . base_url('sobre') . '</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>';
        echo '<url><loc>' . base_url('sobre/batuira') . '</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>';
        echo '<url><loc>' . base_url('contato') . '</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>';
        
        foreach ($magazines as $magazine) {
            echo '<url>';
            echo '<loc>' . base_url('revista/' . $magazine['slug']) . '</loc>';
            echo '<lastmod>' . date('Y-m-d', strtotime($magazine['updated_at'])) . '</lastmod>';
            echo '<changefreq>monthly</changefreq>';
            echo '<priority>0.8</priority>';
            echo '</url>';
        }
        
        foreach ($dialogos as $dialogo) {
            echo '<url>';
            echo '<loc>' . base_url('dialogos/' . $dialogo['slug']) . '</loc>';
            echo '<lastmod>' . date('Y-m-d', strtotime($dialogo['updated_at'])) . '</lastmod>';
            echo '<changefreq>monthly</changefreq>';
            echo '<priority>0.8</priority>';
            echo '</url>';
        }
        
        foreach ($rajianStudies as $study) {
            echo '<url>';
            echo '<loc>' . base_url('rajian/' . $study['slug']) . '</loc>';
            echo '<lastmod>' . date('Y-m-d', strtotime($study['updated_at'])) . '</lastmod>';
            echo '<changefreq>monthly</changefreq>';
            echo '<priority>0.8</priority>';
            echo '</url>';
        }
        
        foreach ($blogPosts as $post) {
            echo '<url>';
            echo '<loc>' . base_url('blog/' . $post['slug']) . '</loc>';
            echo '<lastmod>' . date('Y-m-d', strtotime($post['updated_at'])) . '</lastmod>';
            echo '<changefreq>weekly</changefreq>';
            echo '<priority>0.7</priority>';
            echo '</url>';
        }
        
        echo '</urlset>';
        exit;
    }
}
