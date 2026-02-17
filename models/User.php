<?php
/**
 * Model User (Admin)
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Model.php';

class User extends Model {
    protected $table = 'users_admin';
    
    public function authenticate($email, $password) {
        $user = $this->findBy('email', $email);
        
        if (!$user || $user['status'] !== 'active') {
            return false;
        }
        
        if (password_verify($password, $user['password'])) {
            $this->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            return $user;
        }
        
        return false;
    }
    
    public function createUser($name, $email, $password) {
        return $this->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'status' => 'active'
        ]);
    }
}
