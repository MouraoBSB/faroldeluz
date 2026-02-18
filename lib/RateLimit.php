<?php
/**
 * Classe de Rate Limiting
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-18
 */

class RateLimit {
    private $db;
    
    public function __construct() {
        try {
            $this->db = new PDO(
                "mysql:host=" . DB_CONFIG['host'] . ";port=" . DB_CONFIG['port'] . ";dbname=" . DB_CONFIG['database'] . ";charset=" . DB_CONFIG['charset'],
                DB_CONFIG['username'],
                DB_CONFIG['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            error_log("Erro ao conectar ao banco para rate limit: " . $e->getMessage());
            return;
        }
    }
    
    /**
     * Verifica se o identificador excedeu o limite de tentativas
     * 
     * @param string $identifier Identificador único (ex: IP:login, IP:contact)
     * @param int $maxAttempts Número máximo de tentativas
     * @param int $timeWindow Janela de tempo em segundos
     * @return bool True se permitido, False se bloqueado
     */
    public function check($identifier, $maxAttempts = 5, $timeWindow = 300) {
        if (!$this->db) {
            return true; // Se não conseguir conectar, permite (fail-open)
        }
        
        try {
            // Contar tentativas recentes
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as attempts 
                FROM rate_limits 
                WHERE identifier = ? 
                AND created_at > DATE_SUB(NOW(), INTERVAL ? SECOND)
            ");
            $stmt->execute([$identifier, $timeWindow]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Se excedeu o limite, bloquear
            if ($result['attempts'] >= $maxAttempts) {
                return false;
            }
            
            // Registrar esta tentativa
            $stmt = $this->db->prepare("
                INSERT INTO rate_limits (identifier, created_at) 
                VALUES (?, NOW())
            ");
            $stmt->execute([$identifier]);
            
            // Limpar registros antigos (mais de 1 hora)
            $this->cleanup();
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Erro no rate limit: " . $e->getMessage());
            return true; // Em caso de erro, permite (fail-open)
        }
    }
    
    /**
     * Limpa registros antigos da tabela
     */
    private function cleanup() {
        try {
            // Executar limpeza apenas 10% das vezes para não sobrecarregar
            if (rand(1, 10) === 1) {
                $stmt = $this->db->prepare("
                    DELETE FROM rate_limits 
                    WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 HOUR)
                ");
                $stmt->execute();
            }
        } catch (PDOException $e) {
            error_log("Erro ao limpar rate limits: " . $e->getMessage());
        }
    }
    
    /**
     * Reseta o contador para um identificador
     */
    public function reset($identifier) {
        if (!$this->db) {
            return;
        }
        
        try {
            $stmt = $this->db->prepare("
                DELETE FROM rate_limits WHERE identifier = ?
            ");
            $stmt->execute([$identifier]);
        } catch (PDOException $e) {
            error_log("Erro ao resetar rate limit: " . $e->getMessage());
        }
    }
}
