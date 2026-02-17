<?php
/**
 * Model Rajian
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-16 23:25:00
 */

class Rajian extends Model {
    protected $table = 'rajian';
    
    public function getPublished() {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE status = 'published' 
            AND published_at <= NOW() 
            ORDER BY published_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function paginatePublished($page = 1, $perPage = 9, $search = '', $order = 'recent') {
        $offset = ($page - 1) * $perPage;
        
        $orderBy = match($order) {
            'oldest' => 'published_at ASC',
            'title' => 'title ASC',
            default => 'published_at DESC'
        };
        
        $whereClause = "WHERE status = 'published' AND published_at <= NOW()";
        if ($search) {
            $whereClause .= " AND (title LIKE :search OR description_html LIKE :search)";
        }
        
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            {$whereClause}
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset
        ");
        
        if ($search) {
            $stmt->bindValue(':search', "%{$search}%", PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countPublished($search = '') {
        $whereClause = "WHERE status = 'published' AND published_at <= NOW()";
        if ($search) {
            $whereClause .= " AND (title LIKE :search OR description_html LIKE :search)";
        }
        
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} {$whereClause}");
        
        if ($search) {
            $stmt->bindValue(':search', "%{$search}%", PDO::PARAM_STR);
        }
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    public function searchPublished($query) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE status = 'published' 
            AND published_at <= NOW()
            AND (title LIKE :query OR description_html LIKE :query)
            ORDER BY published_at DESC
        ");
        $stmt->execute(['query' => "%{$query}%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
