# Guia Completo de Configuração Google Drive Backup

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

Este documento detalha o processo completo de configuração de backup automático para Google Drive usando OAuth 2.0, incluindo todos os desafios enfrentados e soluções implementadas.

### Contexto do Projeto

- **Servidor:** Hospedagem compartilhada sem acesso SSH
- **Restrições:** `exec()` desabilitado, sem Composer
- **Objetivo:** Backup automático do banco de dados para Google Drive
- **Método:** Google Drive API v3 com OAuth 2.0

---

## ❌ Problemas Encontrados

### 1. Bibliotecas Não Disponíveis

**Problema:** Servidor não tinha biblioteca Google API Client instalada.

**Tentativa Inicial:** Usar `google/apiclient` via Composer.

**Resultado:** Falhou - sem acesso SSH ou Composer na hospedagem compartilhada.

**Solução:** Implementar chamadas HTTP diretas para Google Drive API usando cURL.

---

### 2. Autenticação OAuth 2.0 Complexa

**Desafio:** OAuth 2.0 requer múltiplas etapas:
1. Criar projeto no Google Cloud Console
2. Configurar tela de consentimento
3. Criar credenciais OAuth
4. Obter código de autorização
5. Trocar código por Refresh Token
6. Usar Refresh Token para obter Access Token

**Problema:** Processo manual e complexo para usuário final.

---

### 3. App em Modo de Teste

**Erro ao autorizar:**
```
Acesso bloqueado: o app não concluiu o processo de verificação do Google
```

**Causa:** App OAuth em modo "Testing" sem usuários de teste cadastrados.

**Solução:** Adicionar email do usuário como "Test User" no Google Cloud Console.

---

### 4. Redirect URI para Desktop App

**Problema:** Aplicação web precisa de redirect URI, mas não temos servidor OAuth.

**Solução:** Usar `urn:ietf:wg:oauth:2.0:oob` (Out-of-Band) para aplicações desktop.

Isso faz o Google exibir o código de autorização na tela para o usuário copiar manualmente.

---

### 5. Refresh Token Não Gerado

**Problema:** Primeira tentativa de autorização não retornava `refresh_token`.

**Causa:** Faltava parâmetros `access_type=offline` e `prompt=consent`.

**Solução:**
```php
$authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
    'client_id' => $clientId,
    'redirect_uri' => 'urn:ietf:wg:oauth:2.0:oob',
    'response_type' => 'code',
    'scope' => 'https://www.googleapis.com/auth/drive.file',
    'access_type' => 'offline',  // IMPORTANTE
    'prompt' => 'consent'         // IMPORTANTE
]);
```

---

### 6. Upload de Arquivo Grande

**Problema:** Arquivos de backup podem ser grandes, causando timeout.

**Solução:** Usar multipart upload com base64 encoding:

```php
$multipartBody = $delimiter;
$multipartBody .= "Content-Type: application/json; charset=UTF-8\r\n\r\n";
$multipartBody .= json_encode($metadata);
$multipartBody .= $delimiter;
$multipartBody .= "Content-Type: application/gzip\r\n";
$multipartBody .= "Content-Transfer-Encoding: base64\r\n\r\n";
$multipartBody .= base64_encode(file_get_contents($filePath));
$multipartBody .= $closeDelimiter;
```

---

## ✅ Solução Final

### Arquitetura Implementada

```
┌─────────────────────────────────────────────────────────────┐
│                    Google Cloud Console                      │
│  ┌────────────────────────────────────────────────────────┐ │
│  │  1. Criar Projeto                                       │ │
│  │  2. Ativar Google Drive API                            │ │
│  │  3. Configurar OAuth Consent Screen                    │ │
│  │  4. Criar OAuth Client ID (Desktop App)                │ │
│  │  5. Obter Client ID e Client Secret                    │ │
│  └────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│              Script Helper (get_gdrive_token.php)            │
│  ┌────────────────────────────────────────────────────────┐ │
│  │  1. Gerar URL de autorização                           │ │
│  │  2. Usuário autoriza no Google                         │ │
│  │  3. Copiar código de autorização                       │ │
│  │  4. Trocar código por Refresh Token                    │ │
│  └────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│           Aplicação PHP (send_to_drive.php)                  │
│  ┌────────────────────────────────────────────────────────┐ │
│  │  1. Usar Refresh Token para obter Access Token        │ │
│  │  2. Fazer upload do arquivo via Google Drive API      │ │
│  │  3. Retornar sucesso/erro                              │ │
│  └────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
                            ↓
                    ┌──────────────┐
                    │ Google Drive │
                    └──────────────┘
```

---

## 🔧 Configuração Passo a Passo

### Passo 1: Criar Projeto no Google Cloud Console

1. Acesse: https://console.cloud.google.com
2. Clique em **"Select a project"** (topo)
3. Clique em **"NEW PROJECT"**
4. Nome: `[Nome do Projeto] Backup`
5. Clique em **"CREATE"**

---

### Passo 2: Ativar Google Drive API

1. Com o projeto selecionado
2. Menu: **"APIs & Services"** → **"Library"**
3. Procure: **"Google Drive API"**
4. Clique em **"Google Drive API"**
5. Clique em **"ENABLE"**

---

### Passo 3: Configurar Tela de Consentimento OAuth

1. Menu: **"APIs & Services"** → **"OAuth consent screen"**
2. Escolha: **"External"** (Externo)
3. Clique em **"CREATE"**

**Preencha:**

- **App name:** `[Nome do Projeto] Backup`
- **User support email:** `seu@email.com`
- **Developer contact email:** `seu@email.com`

4. Clique em **"SAVE AND CONTINUE"** (3 vezes)
5. Clique em **"BACK TO DASHBOARD"**

---

### Passo 4: Adicionar Usuários de Teste

1. Na tela OAuth consent screen
2. Role até **"Test users"**
3. Clique em **"+ ADD USERS"**
4. Digite o email que vai autorizar o app
5. Clique em **"SAVE"**

**⚠️ IMPORTANTE:** Sem isso, você receberá erro "access_denied" ao tentar autorizar.

---

### Passo 5: Criar OAuth Client ID

1. Menu: **"APIs & Services"** → **"Credentials"**
2. Clique em **"+ CREATE CREDENTIALS"**
3. Selecione: **"OAuth client ID"**
4. **Application type:** `Desktop app` ou `App para computador`
5. **Name:** `[Nome do Projeto] Backup Client`
6. Clique em **"CREATE"**

**Copie e guarde:**
- **Client ID** (ex: `123456-abc.apps.googleusercontent.com`)
- **Client Secret** (ex: `GOCSPX-abc123xyz`)

---

### Passo 6: Criar Script Helper

Crie arquivo `get_gdrive_token.php` no servidor:

```php
<?php
$clientId = 'SEU_CLIENT_ID_AQUI';
$clientSecret = 'SEU_CLIENT_SECRET_AQUI';
$redirectUri = 'urn:ietf:wg:oauth:2.0:oob';

echo "<h1>Obter Refresh Token do Google Drive</h1>";

if (!isset($_GET['code'])) {
    $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
        'client_id' => $clientId,
        'redirect_uri' => $redirectUri,
        'response_type' => 'code',
        'scope' => 'https://www.googleapis.com/auth/drive.file',
        'access_type' => 'offline',
        'prompt' => 'consent'
    ]);
    
    echo "<p><a href='{$authUrl}' target='_blank'>Autorizar Google Drive</a></p>";
    echo "<form method='get'>";
    echo "<input type='text' name='code' placeholder='Cole o código aqui'><br>";
    echo "<button type='submit'>Obter Refresh Token</button>";
    echo "</form>";
    
} else {
    $code = $_GET['code'];
    
    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'code' => $code,
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'redirect_uri' => $redirectUri,
        'grant_type' => 'authorization_code'
    ]));
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $tokens = json_decode($response, true);
    
    if (isset($tokens['refresh_token'])) {
        echo "<h2>✅ Sucesso!</h2>";
        echo "<p><strong>Refresh Token:</strong></p>";
        echo "<textarea readonly style='width: 100%; height: 100px;'>{$tokens['refresh_token']}</textarea>";
        echo "<p>Copie o Refresh Token e cole nas configurações de Backup!</p>";
    } else {
        echo "<h2>❌ Erro</h2>";
        echo "<pre>" . print_r($tokens, true) . "</pre>";
    }
}
?>
```

---

### Passo 7: Gerar Refresh Token

1. Edite `get_gdrive_token.php` com suas credenciais
2. Faça upload para o servidor
3. Acesse: `https://seusite.com/get_gdrive_token.php`
4. Clique em **"Autorizar Google Drive"**
5. Faça login e autorize
6. **Copie o código** que aparecer
7. **Cole no campo** e clique em "Obter Refresh Token"
8. **Copie o Refresh Token** gerado

---

### Passo 8: Implementar Envio para Drive

Crie arquivo `backup/send_to_drive.php`:

```php
<?php
function sendToGoogleDrive($filePath, $type = 'database') {
    require_once __DIR__ . '/../config/config.php';
    
    // Buscar configurações do banco
    try {
        $db = new PDO(
            "mysql:host=" . DB_CONFIG['host'] . ";dbname=" . DB_CONFIG['database'],
            DB_CONFIG['username'],
            DB_CONFIG['password']
        );
        
        $stmt = $db->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'backup_gdrive_%'");
        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
        $clientId = $settings['backup_gdrive_client_id'] ?? '';
        $clientSecret = $settings['backup_gdrive_client_secret'] ?? '';
        $refreshToken = $settings['backup_gdrive_refresh_token'] ?? '';
        $folderId = $settings['backup_gdrive_folder_id'] ?? '';
        
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Erro ao conectar ao banco'];
    }
    
    if (empty($clientId) || empty($clientSecret) || empty($refreshToken)) {
        return ['success' => false, 'error' => 'Credenciais não configuradas'];
    }
    
    if (!file_exists($filePath)) {
        return ['success' => false, 'error' => 'Arquivo não encontrado'];
    }
    
    // Obter Access Token
    $accessToken = getAccessToken($clientId, $clientSecret, $refreshToken);
    
    if (!$accessToken) {
        return ['success' => false, 'error' => 'Erro ao obter access token'];
    }
    
    // Preparar upload
    $fileName = basename($filePath);
    $mimeType = 'application/gzip';
    
    $metadata = [
        'name' => $fileName,
        'mimeType' => $mimeType
    ];
    
    if ($folderId) {
        $metadata['parents'] = [$folderId];
    }
    
    // Multipart upload
    $boundary = uniqid();
    $delimiter = "\r\n--{$boundary}\r\n";
    $closeDelimiter = "\r\n--{$boundary}--";
    
    $multipartBody = $delimiter;
    $multipartBody .= "Content-Type: application/json; charset=UTF-8\r\n\r\n";
    $multipartBody .= json_encode($metadata);
    $multipartBody .= $delimiter;
    $multipartBody .= "Content-Type: {$mimeType}\r\n";
    $multipartBody .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $multipartBody .= base64_encode(file_get_contents($filePath));
    $multipartBody .= $closeDelimiter;
    
    // Fazer upload
    $ch = curl_init('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $multipartBody);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$accessToken}",
        "Content-Type: multipart/related; boundary={$boundary}",
        "Content-Length: " . strlen($multipartBody)
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode >= 200 && $httpCode < 300) {
        $result = json_decode($response, true);
        return [
            'success' => true,
            'file_id' => $result['id'] ?? null,
            'file_name' => $fileName
        ];
    } else {
        return [
            'success' => false,
            'error' => "HTTP {$httpCode}: {$response}"
        ];
    }
}

function getAccessToken($clientId, $clientSecret, $refreshToken) {
    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'refresh_token' => $refreshToken,
        'grant_type' => 'refresh_token'
    ]));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        return $data['access_token'] ?? null;
    }
    
    return null;
}
```

---

### Passo 9: Integrar com Backup

No script de backup (`backup_database_pdo.php`):

```php
// Após criar o backup
$stmt = $db->query("SELECT setting_value FROM settings WHERE setting_key = 'backup_gdrive_enabled'");
$gdriveEnabled = $stmt->fetchColumn();

if ($gdriveEnabled === '1') {
    echo "\n🔄 Enviando para Google Drive...\n";
    require_once __DIR__ . '/send_to_drive.php';
    $driveResult = sendToGoogleDrive($gzipFile, 'database');
    
    if ($driveResult['success']) {
        echo "✅ Enviado para Google Drive: {$driveResult['file_name']}\n";
    } else {
        echo "❌ Erro ao enviar: {$driveResult['error']}\n";
    }
}
```

---

### Passo 10: Configurar no Admin

Adicionar campos na interface admin:

```php
<div>
    <label>
        <input type="checkbox" name="backup_gdrive_enabled" value="1">
        Enviar backups para Google Drive
    </label>
</div>

<div>
    <label>Client ID do Google Drive</label>
    <input type="text" name="backup_gdrive_client_id">
</div>

<div>
    <label>Client Secret do Google Drive</label>
    <input type="text" name="backup_gdrive_client_secret">
</div>

<div>
    <label>Refresh Token do Google Drive</label>
    <input type="text" name="backup_gdrive_refresh_token">
</div>

<div>
    <label>ID da Pasta do Google Drive (opcional)</label>
    <input type="text" name="backup_gdrive_folder_id">
    <small>Deixe em branco para salvar na raiz</small>
</div>
```

---

## 🐛 Troubleshooting

### Erro: "access_denied"

**Causa:** Email não está na lista de usuários de teste.

**Solução:**
1. Google Cloud Console
2. OAuth consent screen
3. Test users → Add users
4. Adicione o email e salve

---

### Erro: "invalid_grant"

**Causa:** Refresh Token expirado ou inválido.

**Solução:** Gerar novo Refresh Token:
1. Acesse `get_gdrive_token.php`
2. Autorize novamente
3. Copie o novo Refresh Token
4. Atualize nas configurações

---

### Erro: "insufficient_permissions"

**Causa:** Scope incorreto ou faltando.

**Solução:** Usar scope correto:
```php
'scope' => 'https://www.googleapis.com/auth/drive.file'
```

Este scope permite criar e modificar apenas arquivos criados pelo app.

---

### Refresh Token não é retornado

**Causa:** Faltam parâmetros `access_type` e `prompt`.

**Solução:**
```php
$authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
    // ... outros parâmetros
    'access_type' => 'offline',  // Obrigatório
    'prompt' => 'consent'         // Obrigatório
]);
```

---

### Upload falha com arquivo grande

**Causa:** Timeout ou limite de memória.

**Soluções:**

1. **Aumentar timeout:**
```php
curl_setopt($ch, CURLOPT_TIMEOUT, 300); // 5 minutos
```

2. **Usar resumable upload** para arquivos >5MB:
```php
// Implementar resumable upload conforme documentação Google
```

3. **Comprimir melhor o backup:**
```php
$gz = gzopen($gzipFile, 'w9'); // Máxima compressão
```

---

### Como obter ID da pasta do Drive

1. Acesse Google Drive
2. Crie uma pasta para os backups
3. Abra a pasta
4. Copie o ID da URL:
```
https://drive.google.com/drive/folders/[ID_DA_PASTA]
```
5. Cole o ID nas configurações

---

## 📊 Resultados

### Estatísticas

- **Tempo de upload:** ~2-5 segundos para 0.02 MB
- **Taxa de sucesso:** 100%
- **Custo:** Gratuito (15 GB no Google Drive)
- **Retenção:** Ilimitada (até espaço disponível)

### Vantagens

✅ Backup externo seguro  
✅ Acesso de qualquer lugar  
✅ Versionamento automático  
✅ Integração nativa com Google  
✅ Sem custo adicional  

---

## 🎯 Lições Aprendidas

1. **OAuth 2.0 é complexo** mas necessário para segurança
2. **Refresh Token é permanente** (até ser revogado)
3. **Access Token expira** em 1 hora (renovar sempre)
4. **Test users são obrigatórios** em modo Testing
5. **Out-of-Band redirect** funciona para apps sem servidor OAuth
6. **Multipart upload** é necessário para arquivos binários
7. **Base64 encoding** aumenta tamanho em ~33%

---

## 📚 Referências

- [Google Drive API v3](https://developers.google.com/drive/api/v3/reference)
- [OAuth 2.0](https://developers.google.com/identity/protocols/oauth2)
- [Multipart Upload](https://developers.google.com/drive/api/v3/manage-uploads#multipart)
- [Google Cloud Console](https://console.cloud.google.com)

---

## ✅ Checklist para Novos Projetos

- [ ] Criar projeto no Google Cloud Console
- [ ] Ativar Google Drive API
- [ ] Configurar OAuth consent screen
- [ ] Adicionar usuários de teste
- [ ] Criar OAuth Client ID (Desktop app)
- [ ] Criar script helper para gerar Refresh Token
- [ ] Implementar função de upload
- [ ] Integrar com sistema de backup
- [ ] Adicionar interface admin
- [ ] Testar upload manual
- [ ] Configurar backup automático
- [ ] Documentar credenciais

---

## 🔐 Segurança

### Boas Práticas

1. **Nunca commitar credenciais** no Git
2. **Armazenar no banco de dados** criptografado
3. **Usar HTTPS** sempre
4. **Revogar tokens** não utilizados
5. **Limitar scope** ao mínimo necessário
6. **Monitorar acessos** no Google Cloud Console

### Revogar Acesso

Se precisar revogar o acesso:

1. Google Account → Security
2. Third-party apps with account access
3. Encontre o app
4. Remove access

Depois gere novo Refresh Token.

---

**Fim do documento**
