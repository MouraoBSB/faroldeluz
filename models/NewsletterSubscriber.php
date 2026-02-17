<?php
/**
 * Model NewsletterSubscriber
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Model.php';

class NewsletterSubscriber extends Model {
    protected $table = 'newsletter_subscribers';
    
    public function subscribe($name, $email, $ip = null, $userAgent = null) {
        $existing = $this->findBy('email', $email);
        
        if ($existing) {
            if ($existing['status'] === 'unsubscribed') {
                $this->update($existing['id'], ['status' => 'active']);
            }
            return $existing['id'];
        }
        
        return $this->create([
            'name' => $name,
            'email' => $email,
            'status' => 'active',
            'consent_ip' => $ip,
            'consent_user_agent' => $userAgent
        ]);
    }
    
    public function unsubscribe($email) {
        $subscriber = $this->findBy('email', $email);
        if ($subscriber) {
            return $this->update($subscriber['id'], ['status' => 'unsubscribed']);
        }
        return false;
    }
    
    public function getActive() {
        return $this->where(['status' => 'active'], 'created_at DESC');
    }
}
