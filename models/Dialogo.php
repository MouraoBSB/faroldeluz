<?php
/**
 * Model Dialogo
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Model.php';

class Dialogo extends Model {
    protected $table = 'dialogos';
    
    public function getPublished($limit = null) {
        $dialogos = $this->where(['status' => 'published'], 'published_at DESC', $limit);
        
        foreach ($dialogos as &$dialogo) {
            if (empty($dialogo['youtube_video_id']) && !empty($dialogo['youtube_url'])) {
                $dialogo['youtube_video_id'] = $this->extractYoutubeId($dialogo['youtube_url']);
            }
        }
        
        return $dialogos;
    }
    
    private function extractYoutubeId($url) {
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/live\/)([^"&?\/\s]{11})/', $url, $matches)) {
            return $matches[1];
        }
        return '';
    }
    
    public function findBySlug($slug) {
        $dialogo = $this->findBy('slug', $slug);
        
        if ($dialogo && empty($dialogo['youtube_video_id']) && !empty($dialogo['youtube_url'])) {
            $dialogo['youtube_video_id'] = $this->extractYoutubeId($dialogo['youtube_url']);
        }
        
        return $dialogo;
    }
    
    public function paginatePublished($page = 1, $perPage = 12) {
        return $this->paginate($page, $perPage, ['status' => 'published'], 'published_at DESC');
    }
    
    public function searchPublished($query, $page = 1, $perPage = 12) {
        $offset = ($page - 1) * $perPage;
        
        $whereSQL = 'WHERE status = ? AND (title LIKE ? OR description_html LIKE ?)';
        $params = ['published', "%{$query}%", "%{$query}%"];
        
        $countSQL = "SELECT COUNT(*) as total FROM {$this->table} {$whereSQL}";
        $stmt = $this->db->prepare($countSQL);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];
        
        $dataSQL = "SELECT * FROM {$this->table} {$whereSQL} ORDER BY published_at DESC LIMIT {$perPage} OFFSET {$offset}";
        $stmt = $this->db->prepare($dataSQL);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }
}
