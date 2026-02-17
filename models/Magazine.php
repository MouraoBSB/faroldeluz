<?php
/**
 * Model Magazine
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Model.php';

class Magazine extends Model {
    protected $table = 'magazines';
    
    public function getPublished($limit = null) {
        return $this->where(['status' => 'published'], 'published_at DESC', $limit);
    }
    
    public function getLatest() {
        $result = $this->where(['status' => 'published'], 'published_at DESC', 1);
        return !empty($result) ? $result[0] : null;
    }
    
    public function findBySlug($slug) {
        return $this->findBy('slug', $slug);
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
