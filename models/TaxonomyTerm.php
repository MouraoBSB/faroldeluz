<?php
/**
 * Model TaxonomyTerm
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Model.php';

class TaxonomyTerm extends Model {
    protected $table = 'taxonomy_terms';
    
    public function getByType($type) {
        return $this->where(['taxonomy_type' => $type], 'name ASC');
    }
    
    public function getCategories() {
        return $this->getByType('category');
    }
    
    public function getTags() {
        return $this->getByType('tag');
    }
    
    public function findBySlug($slug, $type) {
        $sql = "SELECT * FROM {$this->table} WHERE slug = ? AND taxonomy_type = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug, $type]);
        return $stmt->fetch();
    }
    
    public function searchByType($type, $search) {
        $sql = "SELECT * FROM {$this->table} WHERE taxonomy_type = ? AND name LIKE ? ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$type, "%{$search}%"]);
        return $stmt->fetchAll();
    }
}
