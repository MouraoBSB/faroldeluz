<?php
/**
 * Model Setting
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-15 16:12:00
 */

require_once BASE_PATH . '/core/Model.php';

class Setting extends Model {
    protected $table = 'settings';
    
    public function get($key, $default = null) {
        $setting = $this->findBy('setting_key', $key);
        return $setting ? $setting['setting_value'] : $default;
    }
    
    public function set($key, $value) {
        $existing = $this->findBy('setting_key', $key);
        
        if ($existing) {
            return $this->update($existing['id'], ['setting_value' => $value]);
        }
        
        return $this->create([
            'setting_key' => $key,
            'setting_value' => $value
        ]);
    }
    
    public function getAll() {
        $settings = $this->all('setting_key ASC');
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
        return $result;
    }
}
