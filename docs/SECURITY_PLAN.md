# Plano de Segurança - Farol de Luz

**Autor:** Thiago Mourão  
**URL:** https://www.instagram.com/mouraoeguerin/  
**Data:** 2026-02-18  
**Versão:** 1.0

---

## 📋 Índice

1. [Análise de Vulnerabilidades](#análise-de-vulnerabilidades)
2. [Proteções Implementadas](#proteções-implementadas)
3. [Proteções a Implementar](#proteções-a-implementar)
4. [Configurações de Servidor](#configurações-de-servidor)
5. [Monitoramento e Logs](#monitoramento-e-logs)
6. [Backup e Recuperação](#backup-e-recuperação)
7. [Checklist de Segurança](#checklist-de-segurança)

---

## 🔍 Análise de Vulnerabilidades

### ✅ Pontos Fortes Atuais

1. **PDO com Prepared Statements**
   - Todo acesso ao banco usa PDO com prepared statements
   - Proteção contra SQL Injection implementada

2. **Sanitização de Inputs**
   - Função `sanitize_input()` usando `htmlspecialchars()`
   - Proteção básica contra XSS

3. **Autenticação de Admin**
   - Sistema de login com sessões
   - Senhas hasheadas com `password_hash()`

4. **HTTPS**
   - Site usa SSL/TLS (Cloudflare)
   - Comunicações criptografadas

5. **Backup Automático**
   - Backup diário do banco de dados
   - Envio para Google Drive (backup externo)

---

### ⚠️ Vulnerabilidades Identificadas

#### 1. **CSRF (Cross-Site Request Forgery)**
**Risco:** Alto  
**Status:** ❌ Não protegido  
**Impacto:** Atacante pode executar ações em nome do admin

**Exemplo de ataque:**
```html
<img src="https://faroldeluz.ong.br/admin/posts/1/deletar">
```

---

#### 2. **Rate Limiting**
**Risco:** Médio  
**Status:** ❌ Não implementado  
**Impacto:** Brute force em login, spam de formulários

---

#### 3. **Headers de Segurança**
**Risco:** Médio  
**Status:** ⚠️ Parcialmente implementado (via Cloudflare)  
**Impacto:** Clickjacking, XSS, MIME sniffing

**Headers faltando:**
- `X-Frame-Options`
- `X-Content-Type-Options`
- `Content-Security-Policy`
- `Referrer-Policy`
- `Permissions-Policy`

---

#### 4. **Upload de Arquivos**
**Risco:** Alto  
**Status:** ⚠️ Validação básica  
**Impacto:** Upload de scripts maliciosos

**Problemas:**
- Validação apenas por extensão
- Sem verificação de MIME type real
- Sem limite de tamanho adequado

---

#### 5. **Exposição de Informações**
**Risco:** Baixo  
**Status:** ⚠️ Alguns arquivos expostos  
**Impacto:** Vazamento de informações sensíveis

**Arquivos expostos:**
- `test_email_direct.php`
- `test_smtp_debug.php`
- `get_gdrive_token.php`
- `clear_cache.php`

---

#### 6. **Logs de Segurança**
**Risco:** Médio  
**Status:** ❌ Não implementado  
**Impacto:** Dificuldade em detectar ataques

---

#### 7. **Validação de Sessão**
**Risco:** Médio  
**Status:** ⚠️ Básica  
**Impacto:** Session hijacking, fixation

**Faltando:**
- Regeneração de session ID após login
- Timeout de sessão
- Validação de IP/User-Agent

---

## 🛡️ Proteções Implementadas

### 1. SQL Injection ✅

**Implementação:**
```php
// Uso de PDO com prepared statements
$stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
```

**Status:** ✅ Protegido em todo o código

---

### 2. XSS Básico ✅

**Implementação:**
```php
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
```

**Status:** ✅ Protegido nas views com `htmlspecialchars()`

---

### 3. Password Hashing ✅

**Implementação:**
```php
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
password_verify($password, $hashedPassword);
```

**Status:** ✅ Senhas nunca armazenadas em texto plano

---

## 🔒 Proteções a Implementar

### 1. CSRF Protection (Prioridade: ALTA)

**Implementação:**

#### Criar classe CSRF
```php
// lib/CSRF.php
class CSRF {
    public static function generateToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function validateToken($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public static function getField() {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
}
```

#### Adicionar em todos os formulários
```php
<?= CSRF::getField() ?>
```

#### Validar em todos os POSTs
```php
if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
    die('Token CSRF inválido');
}
```

---

### 2. Rate Limiting (Prioridade: ALTA)

**Implementação:**

#### Criar classe RateLimit
```php
// lib/RateLimit.php
class RateLimit {
    private $db;
    
    public function check($identifier, $maxAttempts = 5, $timeWindow = 300) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as attempts 
            FROM rate_limits 
            WHERE identifier = ? 
            AND created_at > DATE_SUB(NOW(), INTERVAL ? SECOND)
        ");
        $stmt->execute([$identifier, $timeWindow]);
        $result = $stmt->fetch();
        
        if ($result['attempts'] >= $maxAttempts) {
            return false;
        }
        
        $stmt = $this->db->prepare("
            INSERT INTO rate_limits (identifier, created_at) 
            VALUES (?, NOW())
        ");
        $stmt->execute([$identifier]);
        
        return true;
    }
}
```

#### Usar no login
```php
$identifier = $_SERVER['REMOTE_ADDR'] . ':login';
if (!$rateLimit->check($identifier, 5, 300)) {
    die('Muitas tentativas. Aguarde 5 minutos.');
}
```

---

### 3. Security Headers (Prioridade: MÉDIA)

**Implementação:**

#### Criar arquivo .htaccess
```apache
# Security Headers
<IfModule mod_headers.c>
    # Prevenir clickjacking
    Header always set X-Frame-Options "SAMEORIGIN"
    
    # Prevenir MIME sniffing
    Header always set X-Content-Type-Options "nosniff"
    
    # XSS Protection
    Header always set X-XSS-Protection "1; mode=block"
    
    # Referrer Policy
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    
    # Content Security Policy
    Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdn.tiny.cloud; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self';"
    
    # Permissions Policy
    Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"
</IfModule>
```

---

### 4. Upload Seguro (Prioridade: ALTA)

**Implementação:**

#### Melhorar validação de upload
```php
function validateUpload($file) {
    // Verificar se é imagem real
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mimeType, $allowedMimes)) {
        throw new Exception('Tipo de arquivo não permitido');
    }
    
    // Verificar tamanho (5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception('Arquivo muito grande');
    }
    
    // Gerar nome aleatório
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = bin2hex(random_bytes(16)) . '.' . $extension;
    
    return $newName;
}
```

---

### 5. Proteção de Arquivos Sensíveis (Prioridade: ALTA)

**Implementação:**

#### Criar .htaccess na raiz
```apache
# Bloquear acesso a arquivos sensíveis
<FilesMatch "^(test_|get_gdrive_token|clear_cache|run_migration)">
    Require ip 127.0.0.1
    Require ip SEU_IP_AQUI
</FilesMatch>

# Bloquear acesso a pastas sensíveis
<DirectoryMatch "^.*/\.(git|vscode|idea)">
    Require all denied
</DirectoryMatch>

# Bloquear acesso a arquivos de configuração
<FilesMatch "\.(env|sql|log|md)$">
    Require all denied
</FilesMatch>
```

---

### 6. Session Security (Prioridade: MÉDIA)

**Implementação:**

#### Melhorar configuração de sessão
```php
// config/session.php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Apenas HTTPS
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', 1);
ini_set('session.gc_maxlifetime', 3600); // 1 hora

session_start();

// Regenerar ID após login
function regenerateSession() {
    session_regenerate_id(true);
}

// Validar sessão
function validateSession() {
    if (!isset($_SESSION['user_ip'])) {
        $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    }
    
    if ($_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR'] ||
        $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        session_destroy();
        return false;
    }
    
    // Timeout de inatividade (30 minutos)
    if (isset($_SESSION['last_activity']) && 
        (time() - $_SESSION['last_activity'] > 1800)) {
        session_destroy();
        return false;
    }
    
    $_SESSION['last_activity'] = time();
    return true;
}
```

---

### 7. Logs de Segurança (Prioridade: MÉDIA)

**Implementação:**

#### Criar classe SecurityLogger
```php
// lib/SecurityLogger.php
class SecurityLogger {
    private $logFile;
    
    public function __construct() {
        $this->logFile = __DIR__ . '/../logs/security.log';
    }
    
    public function log($event, $severity = 'INFO', $details = []) {
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'CLI',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'CLI',
            'event' => $event,
            'severity' => $severity,
            'details' => $details,
            'user_id' => $_SESSION['user_id'] ?? null
        ];
        
        $line = json_encode($entry) . "\n";
        file_put_contents($this->logFile, $line, FILE_APPEND);
    }
    
    public function loginAttempt($email, $success) {
        $this->log('LOGIN_ATTEMPT', $success ? 'INFO' : 'WARNING', [
            'email' => $email,
            'success' => $success
        ]);
    }
    
    public function suspiciousActivity($description) {
        $this->log('SUSPICIOUS_ACTIVITY', 'CRITICAL', [
            'description' => $description
        ]);
    }
}
```

---

## 🖥️ Configurações de Servidor

### PHP.ini Recomendado

```ini
; Desabilitar exibição de erros em produção
display_errors = Off
log_errors = On
error_log = /path/to/php-error.log

; Limitar upload
upload_max_filesize = 5M
post_max_size = 6M
max_file_uploads = 5

; Session security
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1

; Desabilitar funções perigosas
disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source
```

---

### .htaccess Completo

```apache
# Forçar HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"
</IfModule>

# Bloquear acesso a arquivos sensíveis
<FilesMatch "^(test_|get_gdrive_token|clear_cache|run_migration)">
    Require ip 127.0.0.1
</FilesMatch>

<FilesMatch "\.(env|sql|log|md|txt)$">
    Require all denied
</FilesMatch>

# Bloquear listagem de diretórios
Options -Indexes

# Proteção contra hotlinking
RewriteCond %{HTTP_REFERER} !^$
RewriteCond %{HTTP_REFERER} !^https://(www\.)?faroldeluz\.ong\.br [NC]
RewriteRule \.(jpg|jpeg|png|gif|webp)$ - [F]
```

---

## 📊 Monitoramento e Logs

### 1. Logs a Monitorar

- **Login attempts** - Detectar brute force
- **Failed requests** - Detectar scans
- **Upload attempts** - Detectar malware
- **Admin actions** - Auditoria
- **Database errors** - Detectar SQL injection

### 2. Ferramentas Recomendadas

- **Cloudflare Analytics** - Tráfego e ataques DDoS
- **Google Search Console** - Malware e hacking
- **Sucuri SiteCheck** - Scanner de malware
- **VirusTotal** - Verificar reputação do domínio

### 3. Alertas Automáticos

Configurar alertas por email para:
- Múltiplas tentativas de login falhadas
- Uploads suspeitos
- Erros críticos no sistema
- Mudanças em arquivos críticos

---

## 💾 Backup e Recuperação

### ✅ Já Implementado

- Backup diário do banco de dados
- Envio para Google Drive
- Retenção de 30 dias
- Compressão GZIP

### 📋 Recomendações Adicionais

1. **Backup de arquivos**
   - Implementar backup semanal de `/uploads`
   - Backup mensal de todo o código

2. **Teste de restauração**
   - Testar restauração mensalmente
   - Documentar processo de recuperação

3. **Backup offsite**
   - ✅ Google Drive (já implementado)
   - Considerar segundo destino (AWS S3, Dropbox)

4. **Versionamento**
   - ✅ Git para código (já implementado)
   - Considerar versionamento de banco

---

## ✅ Checklist de Segurança

### Imediato (Prioridade ALTA)

- [ ] Implementar proteção CSRF
- [ ] Implementar rate limiting no login
- [ ] Proteger arquivos de teste (test_*.php)
- [ ] Melhorar validação de upload de imagens
- [ ] Adicionar logs de segurança

### Curto Prazo (1-2 semanas)

- [ ] Configurar security headers no .htaccess
- [ ] Implementar session security
- [ ] Criar sistema de monitoramento de logs
- [ ] Configurar alertas de segurança
- [ ] Fazer auditoria de código

### Médio Prazo (1 mês)

- [ ] Implementar WAF (Web Application Firewall)
- [ ] Configurar 2FA para admin
- [ ] Implementar Content Security Policy
- [ ] Fazer pentest básico
- [ ] Documentar plano de resposta a incidentes

### Manutenção Contínua

- [ ] Atualizar PHP e dependências mensalmente
- [ ] Revisar logs de segurança semanalmente
- [ ] Testar backups mensalmente
- [ ] Fazer scan de vulnerabilidades trimestralmente
- [ ] Revisar permissões de arquivos semestralmente

---

## 🚨 Plano de Resposta a Incidentes

### Em caso de invasão:

1. **Isolar** - Tirar site do ar temporariamente
2. **Investigar** - Analisar logs para entender o ataque
3. **Limpar** - Remover código malicioso
4. **Restaurar** - Restaurar de backup limpo
5. **Fortalecer** - Implementar proteções adicionais
6. **Monitorar** - Monitorar intensivamente por 30 dias

### Contatos de Emergência

- **Hospedagem:** [Suporte da hospedagem]
- **Cloudflare:** [Suporte Cloudflare]
- **Desenvolvedor:** Thiago Mourão

---

## 📚 Referências

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/PHP_Configuration_Cheat_Sheet.html)
- [Content Security Policy](https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP)
- [Session Security](https://www.php.net/manual/en/session.security.php)

---

**Última atualização:** 2026-02-18  
**Próxima revisão:** 2026-03-18
