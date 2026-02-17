<?php
/**
 * Model BlogPost
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Model.php';

class BlogPost extends Model {
    protected $table = 'blog_posts';
    
    public function getPublished($limit = null) {
        return $this->where(['status' => 'published'], 'published_at DESC', $limit);
    }
    
    public function findBySlug($slug) {
        return $this->findBy('slug', $slug);
    }
    
    public function paginatePublished($page = 1, $perPage = 12) {
        return $this->paginate($page, $perPage, ['status' => 'published'], 'published_at DESC');
    }
    
    public function searchPublished($query, $page = 1, $perPage = 12) {
        $offset = ($page - 1) * $perPage;
        
        $whereSQL = 'WHERE status = ? AND MATCH(title, excerpt, content_html) AGAINST(? IN NATURAL LANGUAGE MODE)';
        $params = ['published', $query];
        
        $countSQL = "SELECT COUNT(*) as total FROM {$this->table} {$whereSQL}";
        $stmt = $this->db->prepare($countSQL);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];
        
        if ($total == 0) {
            $whereSQL = 'WHERE status = ? AND (title LIKE ? OR excerpt LIKE ? OR content_html LIKE ?)';
            $params = ['published', "%{$query}%", "%{$query}%", "%{$query}%"];
            
            $stmt = $this->db->prepare($countSQL);
            $stmt->execute($params);
            $total = $stmt->fetch()['total'];
        }
        
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
    
    public function getTerms($postId) {
        $sql = "SELECT tt.* FROM taxonomy_terms tt
                INNER JOIN taxonomy_relations tr ON tt.id = tr.term_id
                WHERE tr.content_type = 'blog_post' AND tr.content_id = ?
                ORDER BY tt.taxonomy_type, tt.name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }
    
    public function attachTerms($postId, $termIds) {
        $sql = "DELETE FROM taxonomy_relations WHERE content_type = 'blog_post' AND content_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$postId]);
        
        if (!empty($termIds)) {
            $sql = "INSERT INTO taxonomy_relations (content_type, content_id, term_id) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            foreach ($termIds as $termId) {
                $stmt->execute(['blog_post', $postId, $termId]);
            }
        }
    }
    
    // Aliases para compatibilidade com controller
    public function attachTaxonomies($postId, $taxonomyIds) {
        if (!is_array($taxonomyIds)) {
            $taxonomyIds = [$taxonomyIds];
        }
        
        foreach ($taxonomyIds as $taxonomyId) {
            $sql = "INSERT IGNORE INTO taxonomy_relations (content_type, content_id, term_id) VALUES ('blog_post', ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$postId, $taxonomyId]);
        }
    }
    
    public function detachAllTaxonomies($postId) {
        $sql = "DELETE FROM taxonomy_relations WHERE content_type = 'blog_post' AND content_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$postId]);
    }
    
    public function getPostTaxonomies($postId) {
        return $this->getTerms($postId);
    }
    
    public function getPostsByTaxonomy($taxonomyId, $page = 1, $perPage = 12) {
        $offset = ($page - 1) * $perPage;
        
        $countSQL = "SELECT COUNT(DISTINCT bp.id) as total FROM blog_posts bp
                     INNER JOIN taxonomy_relations tr ON bp.id = tr.content_id
                     WHERE tr.term_id = ? AND tr.content_type = 'blog_post' AND bp.status = 'published'";
        $stmt = $this->db->prepare($countSQL);
        $stmt->execute([$taxonomyId]);
        $total = $stmt->fetch()['total'];
        
        $dataSQL = "SELECT DISTINCT bp.* FROM blog_posts bp
                    INNER JOIN taxonomy_relations tr ON bp.id = tr.content_id
                    WHERE tr.term_id = ? AND tr.content_type = 'blog_post' AND bp.status = 'published'
                    ORDER BY bp.published_at DESC LIMIT {$perPage} OFFSET {$offset}";
        $stmt = $this->db->prepare($dataSQL);
        $stmt->execute([$taxonomyId]);
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
