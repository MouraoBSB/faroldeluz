# Guia Completo de Configuração SMTP

**Autor:** Thiago Mourão  
**URL:** https://www.instagram.com/mouraoeguerin/  
**Data:** 2026-02-17  
**Projeto:** Farol de Luz

---

## 📋 Índice

1. [Visão Geral](#visão-geral)
2. [Problemas Encontrados](#problemas-encontrados)
3. [Solução Final](#solução-final)
4. [Configuração Passo a Passo](#configuração-passo-a-passo)
5. [Troubleshooting](#troubleshooting)
6. [Código Implementado](#código-implementado)

---

## 🎯 Visão Geral

Este documento detalha todo o processo de configuração de envio de emails via SMTP em um servidor de hospedagem compartilhada, incluindo todos os desafios enfrentados e as soluções implementadas.

### Contexto do Projeto

- **Servidor:** Hospedagem compartilhada (cPanel)
- **Restrições:** `mail()` e `exec()` desabilitados por segurança
- **Objetivo:** Enviar emails de notificação e relatórios de backup
- **Provedor SMTP:** pro115.dnspro.com.br (servidor da hospedagem)

---

## ❌ Problemas Encontrados

### 1. Função `mail()` Desabilitada

**Erro:**
```
mail() has been disabled for security reasons
```

**Causa:** Servidores de hospedagem compartilhada frequentemente desabilitam `mail()` para evitar spam.

**Tentativa Inicial:** Usar `mail()` do PHP com configurações SMTP via `ini_set()`.

**Resultado:** Falhou - função completamente desabilitada.

---

### 2. PHPMailer Não Disponível

**Problema:** Servidor não tinha PHPMailer instalado e não permitia instalação via Composer.

**Tentativa:** Tentar usar biblioteca externa.

**Resultado:** Inviável sem acesso SSH ou Composer.

---

### 3. Implementação SMTP Manual - Primeira Tentativa

**Problema:** Conexão SSL falhando com `fsockopen()`.

**Erro:**
```
fsockopen(): SSL operation failed with code 1
```

**Causa:** Configuração incorreta de contexto SSL.

---

### 4. Emails Não Chegando no Gmail

**Problema:** Emails eram "enviados" mas não chegavam no Gmail.

**Causa:** Falta de configuração SPF/DKIM/DMARC no DNS.

**Sintoma:** Gmail bloqueava silenciosamente os emails.

---

### 5. Registros SPF Duplicados

**Erro no cPanel:**
```
SPF record not valid - Multiple SPF records found
```

**Problema:** Dois registros TXT com `v=spf1` no Cloudflare.

**Causa:** Configuração manual anterior + configuração automática do cPanel.

---

### 6. Codificação de Senha

**Problema:** Senha SMTP sendo salva com caracteres HTML escapados (`&` virava `&amp;`).

**Causa:** `htmlspecialchars()` sendo aplicado em todos os campos do formulário.

**Sintoma:** Autenticação SMTP falhava com senha incorreta.

---

## ✅ Solução Final

### Arquitetura Implementada

```
┌─────────────────────────────────────────────────────────┐
│                    Aplicação PHP                         │
│  ┌────────────────────────────────────────────────────┐ │
│  │         Classe Mailer (lib/Mailer.php)             │ │
│  │                                                     │ │
│  │  • Busca configurações direto do banco (PDO)       │ │
│  │  • Implementa SMTP via stream_socket_client        │ │
│  │  • Suporta SSL/TLS                                 │ │
│  │  • Autenticação AUTH LOGIN                         │ │
│  └────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────┘
                            ↓
                    stream_socket_client
                            ↓
                ┌──────────────────────┐
                │   Servidor SMTP      │
                │ pro115.dnspro.com.br │
                │      Porta 465       │
                │       SSL/TLS        │
                └──────────────────────┘
                            ↓
                    ┌──────────────┐
                    │ Destinatário │
                    └──────────────┘
```

---

## 🔧 Configuração Passo a Passo

### Passo 1: Obter Configurações SMTP

Acesse o cPanel da hospedagem e procure por "Email Accounts" ou "Configurações de Email".

**Configurações obtidas:**
- **Host:** `pro115.dnspro.com.br`
- **Porta:** `465` (SSL) ou `587` (TLS)
- **Usuário:** `contato@faroldeluz.ong.br`
- **Senha:** (senha do email)
- **Criptografia:** SSL

---

### Passo 2: Configurar SPF no DNS

**Problema:** Emails bloqueados pelo Gmail por falta de SPF.

**Solução:** Adicionar registro TXT no Cloudflare.

#### No Cloudflare:

1. Acesse: https://dash.cloudflare.com
2. Selecione o domínio
3. Vá em **DNS**
4. **Delete** registros SPF duplicados (se houver)
5. **Adicione** um único registro TXT:

```
Type: TXT
Name: @
Content: v=spf1 +a +mx +ip4:186.209.113.101 include:_spf.dnspro.com.br ~all
Proxy: DNS only (cinza/desligado)
TTL: Auto
```

**Explicação:**
- `v=spf1` - Versão do SPF
- `+a` - Autoriza IP do registro A
- `+mx` - Autoriza servidor MX
- `+ip4:186.209.113.101` - Autoriza IP específico
- `include:_spf.dnspro.com.br` - Inclui regras da hospedagem
- `~all` - Soft fail para outros (recomendado)

---

### Passo 3: Implementar Classe Mailer

Criar arquivo `lib/Mailer.php`:

```php
<?php
class Mailer {
    private $config;
    
    public function __construct() {
        $this->loadConfig();
    }
    
    private function loadConfig() {
        try {
            $db = new PDO(
                "mysql:host=" . DB_CONFIG['host'] . ";dbname=" . DB_CONFIG['database'],
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
                'user' => $settings['smtp_user'] ?? '',
                'password' => $settings['smtp_password'] ?? '',
                'encryption' => $settings['smtp_encryption'] ?? 'ssl',
                'from_name' => $settings['smtp_from_name'] ?? 'Site',
                'from_email' => $settings['smtp_from_email'] ?? ''
            ];
        } catch (Exception $e) {
            // Fallback para configurações padrão
            $this->config = [
                'host' => 'pro115.dnspro.com.br',
                'port' => 465,
                'user' => '',
                'password' => '',
                'encryption' => 'ssl',
                'from_name' => 'Site',
                'from_email' => ''
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
            
            // Contexto SSL para aceitar certificados auto-assinados
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ]);
            
            // Conectar ao servidor SMTP
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
            
            // EHLO
            fputs($socket, "EHLO {$this->config['host']}\r\n");
            $this->getResponse($socket);
            
            // STARTTLS para porta 587
            if ($this->config['encryption'] === 'tls') {
                fputs($socket, "STARTTLS\r\n");
                $this->getResponse($socket);
                stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                fputs($socket, "EHLO {$this->config['host']}\r\n");
                $this->getResponse($socket);
            }
            
            // Autenticação
            fputs($socket, "AUTH LOGIN\r\n");
            $this->getResponse($socket);
            
            fputs($socket, base64_encode($this->config['user']) . "\r\n");
            $this->getResponse($socket);
            
            fputs($socket, base64_encode($this->config['password']) . "\r\n");
            $this->getResponse($socket);
            
            // Remetente
            fputs($socket, "MAIL FROM: <{$this->config['from_email']}>\r\n");
            $this->getResponse($socket);
            
            // Destinatário
            fputs($socket, "RCPT TO: <{$to}>\r\n");
            $this->getResponse($socket);
            
            // Dados
            fputs($socket, "DATA\r\n");
            $this->getResponse($socket);
            
            // Headers e corpo
            $headers = "From: {$this->config['from_name']} <{$this->config['from_email']}>\r\n";
            $headers .= "Reply-To: {$this->config['from_email']}\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: " . ($isHtml ? "text/html" : "text/plain") . "; charset=UTF-8\r\n";
            $headers .= "Subject: {$subject}\r\n";
            
            $message = $headers . "\r\n" . $body . "\r\n.\r\n";
            fputs($socket, $message);
            $this->getResponse($socket);
            
            // Encerrar
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
}
```

---

### Passo 4: Corrigir Salvamento de Senha

**Problema:** Senha sendo escapada com `htmlspecialchars()`.

**Solução:** Excluir campo `smtp_password` da sanitização.

No `SettingsController.php`:

```php
foreach ($settingsToUpdate as $key) {
    if (isset($_POST[$key])) {
        $value = $_POST[$key];
        
        // Não sanitizar campos específicos
        if ($key !== 'smtp_password' && 
            $key !== 'revista_descricao' && 
            // ... outros campos HTML
        ) {
            $value = sanitize_input($value);
        }
        
        $this->settingModel->set($key, $value);
    }
}
```

---

### Passo 5: Adicionar Interface Admin

Criar campos na interface de configurações:

```php
<div>
    <label>Host SMTP</label>
    <input type="text" name="smtp_host" value="<?= htmlspecialchars($settings['smtp_host'] ?? '') ?>">
</div>

<div>
    <label>Porta SMTP</label>
    <input type="number" name="smtp_port" value="<?= htmlspecialchars($settings['smtp_port'] ?? '') ?>">
</div>

<div>
    <label>Usuário SMTP</label>
    <input type="text" name="smtp_user" value="<?= htmlspecialchars($settings['smtp_user'] ?? '') ?>">
</div>

<div>
    <label>Senha SMTP</label>
    <div class="relative">
        <input type="password" id="smtp_password" name="smtp_password" 
               value="<?= htmlspecialchars($settings['smtp_password'] ?? '') ?>">
        <button type="button" onclick="togglePasswordVisibility()">👁️</button>
    </div>
</div>

<div>
    <label>Criptografia</label>
    <select name="smtp_encryption">
        <option value="tls">TLS (porta 587)</option>
        <option value="ssl">SSL (porta 465)</option>
    </select>
</div>
```

---

## 🐛 Troubleshooting

### Email não chega no destinatário

**Possíveis causas:**

1. **SPF não configurado**
   - Verifique no cPanel: Email Deliverability
   - Adicione registro SPF no DNS

2. **Senha incorreta**
   - Verifique se a senha foi salva corretamente
   - Use o botão de visualizar senha (👁️)
   - Tente fazer login no webmail com a mesma senha

3. **Porta bloqueada**
   - Teste porta 465 (SSL)
   - Se falhar, tente porta 587 (TLS)

4. **Certificado SSL inválido**
   - Use `verify_peer => false` no contexto SSL
   - Isso é seguro em hospedagem compartilhada confiável

---

### Erro: "SSL operation failed"

**Solução:** Usar `stream_socket_client` ao invés de `fsockopen`:

```php
$context = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    ]
]);

$socket = stream_socket_client(
    "ssl://{$host}:{$port}",
    $errno,
    $errstr,
    30,
    STREAM_CLIENT_CONNECT,
    $context
);
```

---

### Erro: "mail() has been disabled"

**Solução:** Implementar SMTP manual via socket (como mostrado acima).

**NÃO use:** `mail()`, `ini_set('SMTP')`, ou bibliotecas que dependem de `mail()`.

---

### Gmail bloqueia emails

**Checklist:**

- [ ] SPF configurado no DNS
- [ ] DKIM configurado (opcional mas recomendado)
- [ ] DMARC configurado (opcional)
- [ ] Remetente válido (email do domínio)
- [ ] Conteúdo não parece spam

**Verificar SPF:**
```bash
nslookup -type=TXT faroldeluz.ong.br
```

---

## 📊 Resultados

### Antes vs Depois

| Aspecto | Antes | Depois |
|---------|-------|--------|
| Função usada | `mail()` | SMTP via socket |
| Taxa de entrega | 0% | 100% |
| Suporte SSL/TLS | ❌ | ✅ |
| Autenticação | ❌ | ✅ AUTH LOGIN |
| SPF configurado | ❌ | ✅ |
| Emails no Gmail | Bloqueados | Entregues |

---

## 🎯 Lições Aprendidas

1. **Sempre verificar restrições do servidor** antes de escolher a solução
2. **SPF é essencial** para entrega de emails
3. **Testar com script direto** antes de integrar na aplicação
4. **Não usar `htmlspecialchars()` em senhas**
5. **Implementar SMTP manual** é mais confiável que `mail()`
6. **Contexto SSL** deve desabilitar verificação em hospedagem compartilhada

---

## 📚 Referências

- [RFC 5321 - SMTP](https://tools.ietf.org/html/rfc5321)
- [RFC 7208 - SPF](https://tools.ietf.org/html/rfc7208)
- [PHP stream_socket_client](https://www.php.net/manual/en/function.stream-socket-client.php)
- [Cloudflare DNS](https://developers.cloudflare.com/dns/)

---

## ✅ Checklist para Novos Projetos

- [ ] Verificar se `mail()` está disponível
- [ ] Obter configurações SMTP da hospedagem
- [ ] Configurar SPF no DNS
- [ ] Implementar classe Mailer via socket
- [ ] Adicionar interface admin para configurações
- [ ] Criar script de teste de email
- [ ] Testar envio para Gmail
- [ ] Verificar Email Deliverability no cPanel
- [ ] Documentar configurações

---

**Fim do documento**
