<?php
/**
 * Base Model
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

class Model {
    protected $db;
    protected $table;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function all($orderBy = 'id DESC', $limit = null) {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy}";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function findBy($column, $value) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = ?");
        $stmt->execute([$value]);
        return $stmt->fetch();
    }
    
    public function where($conditions, $orderBy = 'id DESC', $limit = null) {
        $whereClauses = [];
        $params = [];
        
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "{$column} = ?";
            $params[] = $value;
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $whereClauses) . " ORDER BY {$orderBy}";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));
        
        return $this->db->lastInsertId();
    }
    
    public function update($id, $data) {
        $setClauses = [];
        $params = [];
        
        foreach ($data as $column => $value) {
            $setClauses[] = "{$column} = ?";
            $params[] = $value;
        }
        $params[] = $id;
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClauses) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function count($conditions = []) {
        $whereClauses = [];
        $params = [];
        
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "{$column} = ?";
            $params[] = $value;
        }
        
        $whereSQL = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
        
        $sql = "SELECT COUNT(*) as total FROM {$this->table} {$whereSQL}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['total'];
    }
    
    public function paginate($page = 1, $perPage = 12, $conditions = [], $orderBy = 'id DESC') {
        $offset = ($page - 1) * $perPage;
        
        $whereClauses = [];
        $params = [];
        
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "{$column} = ?";
            $params[] = $value;
        }
        
        $whereSQL = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
        
        $countSQL = "SELECT COUNT(*) as total FROM {$this->table} {$whereSQL}";
        $stmt = $this->db->prepare($countSQL);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];
        
        $dataSQL = "SELECT * FROM {$this->table} {$whereSQL} ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}";
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
    
    public function search($query, $fields, $page = 1, $perPage = 12) {
        $offset = ($page - 1) * $perPage;
        
        $searchClauses = [];
        $params = [];
        
        foreach ($fields as $field) {
            $searchClauses[] = "{$field} LIKE ?";
            $params[] = "%{$query}%";
        }
        
        $whereSQL = 'WHERE ' . implode(' OR ', $searchClauses);
        
        $countSQL = "SELECT COUNT(*) as total FROM {$this->table} {$whereSQL}";
        $stmt = $this->db->prepare($countSQL);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];
        
        $dataSQL = "SELECT * FROM {$this->table} {$whereSQL} ORDER BY id DESC LIMIT {$perPage} OFFSET {$offset}";
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
