<?php
/**
 * Classe Mailer - Envio de emails via SMTP
 * Autor: Thiago Mourão
 * URL: https://www.instagram.com/mouraoeguerin/
 * Data: 2026-02-17
 */

class Mailer {
    private $config;
    
    public function __construct() {
        $this->loadConfig();
    }
    
    private function loadConfig() {
        try {
            $db = new PDO(
                "mysql:host=" . DB_CONFIG['host'] . ";port=" . DB_CONFIG['port'] . ";dbname=" . DB_CONFIG['database'] . ";charset=" . DB_CONFIG['charset'],
                DB_CONFIG['username'],
                DB_CONFIG['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            $stmt = $db->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'smtp_%'");
            $settings = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
            
            $this->config = [
                'host' => $settings['smtp_host'] ?? 'pro115.dnspro.com.br',
                'port' => (int)($settings['smtp_port'] ?? 465),
                'user' => $settings['smtp_user'] ?? 'contato@faroldeluz.ong.br',
                'password' => $settings['smtp_password'] ?? '',
                'encryption' => $settings['smtp_encryption'] ?? 'ssl',
                'from_name' => $settings['smtp_from_name'] ?? 'Farol de Luz',
                'from_email' => $settings['smtp_from_email'] ?? 'contato@faroldeluz.ong.br'
            ];
        } catch (Exception $e) {
            $this->config = [
                'host' => 'pro115.dnspro.com.br',
                'port' => 465,
                'user' => 'contato@faroldeluz.ong.br',
                'password' => '',
                'encryption' => 'ssl',
                'from_name' => 'Farol de Luz',
                'from_email' => 'contato@faroldeluz.ong.br'
            ];
        }
    }
    
    public function send($to, $subject, $body, $isHtml = true) {
        try {
            $host = $this->config['host'];
            $port = $this->config['port'];
            
            if ($this->config['encryption'] === 'ssl') {
                $host = 'ssl://' . $host;
            }
            
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ]);
            
            $socket = stream_socket_client(
                "{$host}:{$port}",
                $errno,
                $errstr,
                30,
                STREAM_CLIENT_CONNECT,
                $context
            );
            
            if (!$socket) {
                throw new Exception("Erro ao conectar SMTP: {$errstr} ({$errno})");
            }
            
            stream_set_timeout($socket, 30);
            $this->getResponse($socket);
            
            fputs($socket, "EHLO {$this->config['host']}\r\n");
            $this->getResponse($socket);
            
            if ($this->config['encryption'] === 'tls') {
                fputs($socket, "STARTTLS\r\n");
                $this->getResponse($socket);
                stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                fputs($socket, "EHLO {$this->config['host']}\r\n");
                $this->getResponse($socket);
            }
            
            fputs($socket, "AUTH LOGIN\r\n");
            $this->getResponse($socket);
            
            fputs($socket, base64_encode($this->config['user']) . "\r\n");
            $this->getResponse($socket);
            
            fputs($socket, base64_encode($this->config['password']) . "\r\n");
            $this->getResponse($socket);
            
            fputs($socket, "MAIL FROM: <{$this->config['from_email']}>\r\n");
            $this->getResponse($socket);
            
            fputs($socket, "RCPT TO: <{$to}>\r\n");
            $this->getResponse($socket);
            
            fputs($socket, "DATA\r\n");
            $this->getResponse($socket);
            
            $headers = "From: {$this->config['from_name']} <{$this->config['from_email']}>\r\n";
            $headers .= "Reply-To: {$this->config['from_email']}\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: " . ($isHtml ? "text/html" : "text/plain") . "; charset=UTF-8\r\n";
            $headers .= "Subject: {$subject}\r\n";
            
            $message = $headers . "\r\n" . $body . "\r\n.\r\n";
            fputs($socket, $message);
            $this->getResponse($socket);
            
            fputs($socket, "QUIT\r\n");
            $this->getResponse($socket);
            
            fclose($socket);
            return true;
            
        } catch (Exception $e) {
            error_log("Erro SMTP: " . $e->getMessage());
            return false;
        }
    }
    
    private function getResponse($socket) {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) == ' ') {
                break;
            }
        }
        return $response;
    }
    
    public function sendTest($to) {
        $subject = "Teste de Email - Farol de Luz";
        $body = "
            <html>
            <body style='font-family: Arial, sans-serif;'>
                <h2 style='color: #E8B86D;'>✅ Email de Teste</h2>
                <p>Se você está recebendo este email, significa que as configurações de SMTP estão corretas!</p>
                <p><strong>Configurações atuais:</strong></p>
                <ul>
                    <li>Host: {$this->config['host']}</li>
                    <li>Porta: {$this->config['port']}</li>
                    <li>Usuário: {$this->config['user']}</li>
                    <li>Criptografia: {$this->config['encryption']}</li>
                </ul>
                <hr>
                <p style='color: #8FA3C1; font-size: 12px;'>
                    Farol de Luz - A Luz do Consolador para os dias de hoje!
                </p>
            </body>
            </html>
        ";
        
        return $this->send($to, $subject, $body, true);
    }
}
