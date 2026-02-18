# 🔐 Sistema de Backup Automático - Farol de Luz

**Autor:** Thiago Mourão  
**Data:** 2026-02-17  
**Versão:** 1.0

---

## 📋 Índice

1. [Visão Geral](#visão-geral)
2. [Estrutura de Arquivos](#estrutura-de-arquivos)
3. [Configuração Inicial](#configuração-inicial)
4. [Configuração do Email (SMTP)](#configuração-do-email-smtp)
5. [Configuração do Google Drive](#configuração-do-google-drive)
6. [Configuração do Cron](#configuração-do-cron)
7. [Painel Administrativo](#painel-administrativo)
8. [Restauração de Backups](#restauração-de-backups)
9. [Troubleshooting](#troubleshooting)

---

## 🎯 Visão Geral

Sistema completo de backup híbrido que:

- ✅ **Backup diário** do banco de dados MySQL (3h da madrugada)
- ✅ **Backup semanal** de arquivos/uploads (domingo, 4h)
- ✅ **Armazenamento local** no servidor
- ✅ **Sincronização automática** com Google Drive
- ✅ **Retenção de 30 dias** (configurável)
- ✅ **Relatório semanal** por email
- ✅ **Alertas** em caso de falha
- ✅ **Painel admin** para gerenciar tudo

---

## 📁 Estrutura de Arquivos

```
/backup/
├── database/                      # Backups do MySQL
│   ├── backup_2026-02-17_03-00-00.sql.gz
│   ├── backup_2026-02-16_03-00-00.sql.gz
│   └── backup.log                 # Log de todas as operações
├── files/                         # Backups de uploads
│   ├── files_2026-02-17.tar.gz
│   └── files_2026-02-10.tar.gz
├── backup_database.php            # Script de backup do banco
├── backup_files.php               # Script de backup de arquivos
├── send_to_drive.php              # Envio para Google Drive
└── weekly_report.php              # Relatório semanal

/lib/
└── Mailer.php                     # Classe de envio de emails

/database/migrations/
└── 021_add_email_backup_settings.sql
```

---

## ⚙️ Configuração Inicial

### 1. Executar Migration

Acesse: `https://faroldeluz.ong.br/run_migration_021.php`

Isso criará todas as configurações necessárias no banco de dados.

### 2. Criar Diretórios

Os diretórios são criados automaticamente, mas você pode criá-los manualmente:

```bash
mkdir -p /backup/database
mkdir -p /backup/files
chmod 755 /backup
chmod 755 /backup/database
chmod 755 /backup/files
```

### 3. Testar Permissões

```bash
# Verificar se o PHP pode escrever
touch /backup/database/test.txt
rm /backup/database/test.txt
```

---

## 📧 Configuração do Email (SMTP)

### Passo 1: Acessar Admin

1. Acesse: `https://faroldeluz.ong.br/admin/configuracoes`
2. Clique na aba **"Email/SMTP"**

### Passo 2: Configurar SMTP da Hospedagem

Preencha os campos:

```
Host SMTP: mail.faroldeluz.ong.br
Porta: 587
Usuário: contato@faroldeluz.ong.br
Senha: [sua senha do email]
Criptografia: TLS
Nome do Remetente: Farol de Luz
Email do Remetente: contato@faroldeluz.ong.br
```

### Passo 3: Testar Email

1. Clique em **"Enviar Email de Teste"**
2. Verifique sua caixa de entrada
3. Se não receber, verifique:
   - Spam/Lixo eletrônico
   - Senha do email está correta
   - Porta e host estão corretos

### Configurações Alternativas

**Se usar porta 465 (SSL):**
```
Porta: 465
Criptografia: SSL
```

**Se o servidor SMTP for diferente:**
```bash
# Descobrir servidor SMTP correto
nslookup -type=mx faroldeluz.ong.br
```

---

## 🔐 Configuração do Google Drive

### Passo 1: Criar Projeto no Google Cloud

1. Acesse: https://console.cloud.google.com
2. Clique em **"Criar Projeto"**
3. Nome: `Farol de Luz Backup`
4. Clique em **"Criar"**

### Passo 2: Ativar Google Drive API

1. No menu lateral: **"APIs e Serviços" > "Biblioteca"**
2. Busque: `Google Drive API`
3. Clique em **"Ativar"**

### Passo 3: Criar Credenciais OAuth 2.0

1. **"APIs e Serviços" > "Credenciais"**
2. Clique em **"Criar Credenciais" > "ID do cliente OAuth"**
3. Tipo de aplicativo: **"Aplicativo da Web"**
4. Nome: `Farol de Luz Backup`
5. **URIs de redirecionamento autorizados:**
   ```
   https://faroldeluz.ong.br/admin/backup/oauth-callback
   ```
6. Clique em **"Criar"**
7. **Copie o Client ID e Client Secret**

### Passo 4: Configurar no Admin

1. Acesse: `https://faroldeluz.ong.br/admin/configuracoes`
2. Aba **"Backup e Segurança"**
3. Seção **"Google Drive"**
4. Cole:
   - **Client ID**
   - **Client Secret**
5. Clique em **"Salvar Configurações"**

### Passo 5: Autenticar

1. Clique em **"Autenticar com Google"**
2. Faça login na sua conta Google
3. Autorize o acesso ao Drive
4. Você será redirecionado de volta
5. O **Refresh Token** será salvo automaticamente

### Passo 6: Criar Pasta no Drive (Opcional)

1. Acesse seu Google Drive
2. Crie uma pasta: `Backups Farol de Luz`
3. Abra a pasta
4. Copie o ID da URL:
   ```
   https://drive.google.com/drive/folders/1ABC...XYZ
                                           ↑ Este é o ID
   ```
5. Cole no campo **"ID da Pasta"** no admin

---

## ⏰ Configuração do Cron

### Opção 1: Via cPanel (Mais Fácil)

1. Acesse o **cPanel** da hospedagem
2. Procure por **"Cron Jobs"** ou **"Tarefas Agendadas"**
3. Adicione as seguintes tarefas:

**Backup Diário do Banco (3h da madrugada):**
```
0 3 * * * /usr/bin/php /home/usuario/public_html/backup/backup_database.php
```

**Backup Semanal de Arquivos (domingo, 4h):**
```
0 4 * * 0 /usr/bin/php /home/usuario/public_html/backup/backup_files.php
```

**Relatório Semanal (segunda, 9h):**
```
0 9 * * 1 /usr/bin/php /home/usuario/public_html/backup/weekly_report.php
```

### Opção 2: Via SSH

```bash
# Editar crontab
crontab -e

# Adicionar linhas:
0 3 * * * /usr/bin/php /caminho/completo/backup/backup_database.php
0 4 * * 0 /usr/bin/php /caminho/completo/backup/backup_files.php
0 9 * * 1 /usr/bin/php /caminho/completo/backup/weekly_report.php

# Salvar e sair
```

### Verificar Cron

```bash
# Listar tarefas agendadas
crontab -l

# Ver logs do cron
tail -f /var/log/cron
```

---

## 🎛️ Painel Administrativo

### Acessar

`https://faroldeluz.ong.br/admin/configuracoes`

### Aba "Email/SMTP"

- Configurar servidor de email
- Testar envio de emails
- Ver status da conexão

### Aba "Backup e Segurança"

**Backup do Banco de Dados:**
- ☑️ Ativar/Desativar
- 🕐 Horário de execução
- 📅 Dias de retenção

**Backup de Arquivos:**
- ☑️ Ativar/Desativar
- 📅 Frequência (diário/semanal)

**Google Drive:**
- 🔑 Client ID
- 🔐 Client Secret
- 🔄 Refresh Token
- 📁 ID da Pasta
- 🔗 Botão "Autenticar"

**Notificações:**
- 📧 Email para relatórios
- ☑️ Relatório semanal
- ☑️ Alertar em falhas

**Ações Manuais:**
- 🔄 Fazer backup agora
- 📥 Baixar último backup
- 📊 Ver logs
- 🗑️ Limpar backups antigos

---

## 🔄 Restauração de Backups

### Restaurar Banco de Dados

#### Via Admin (Em breve)

1. Acesse: `https://faroldeluz.ong.br/admin/backup`
2. Selecione o backup desejado
3. Clique em **"Restaurar"**
4. Confirme a ação

#### Via SSH/Terminal

```bash
# 1. Baixar backup do servidor ou Drive
cd /backup/database

# 2. Descompactar
gunzip backup_2026-02-17_03-00-00.sql.gz

# 3. Restaurar
mysql -h 186.209.113.101 -u usuario -p cemaneto_site_faroldeluz < backup_2026-02-17_03-00-00.sql

# Digite a senha quando solicitado
```

#### Via phpMyAdmin

1. Acesse phpMyAdmin
2. Selecione o banco `cemaneto_site_faroldeluz`
3. Aba **"Importar"**
4. Escolha o arquivo `.sql` (descompactado)
5. Clique em **"Executar"**

### Restaurar Arquivos

```bash
# 1. Baixar backup
cd /backup/files

# 2. Descompactar
tar -xzf files_2026-02-17.tar.gz

# 3. Copiar de volta
cp -r uploads/* /caminho/assets/uploads/

# Ou sobrescrever tudo
rm -rf /caminho/assets/uploads
mv uploads /caminho/assets/
```

---

## 🔧 Troubleshooting

### Problema 1: Backup não está rodando

**Verificar:**
```bash
# Cron está configurado?
crontab -l

# Permissões dos scripts
ls -la /backup/*.php

# Testar script manualmente
php /backup/backup_database.php
```

**Solução:**
- Verificar se cron está ativo
- Corrigir caminho do PHP: `which php`
- Verificar permissões: `chmod +x backup_database.php`

### Problema 2: Erro "mysqldump: command not found"

**Solução:**
```bash
# Encontrar mysqldump
which mysqldump

# Atualizar script com caminho completo
/usr/bin/mysqldump --host=...
```

### Problema 3: Email não está sendo enviado

**Verificar:**
1. Configurações SMTP estão corretas?
2. Senha do email está correta?
3. Porta e criptografia corretas?
4. Firewall bloqueando porta 587/465?

**Testar:**
```bash
# Via telnet
telnet mail.faroldeluz.ong.br 587
```

### Problema 4: Google Drive retorna erro 401

**Solução:**
1. Refresh Token expirou
2. Refazer autenticação OAuth
3. Verificar se API está ativa no Google Cloud

### Problema 5: Backup muito grande

**Solução:**
```bash
# Ver tamanho dos backups
du -sh /backup/*

# Reduzir retenção (de 30 para 15 dias)
# No admin: Backup > Retenção: 15 dias

# Limpar backups antigos manualmente
find /backup/database -name "*.gz" -mtime +15 -delete
```

### Problema 6: Sem espaço em disco

**Verificar:**
```bash
# Espaço disponível
df -h

# Tamanho dos backups
du -sh /backup
```

**Solução:**
- Reduzir dias de retenção
- Ativar apenas Google Drive (desativar local)
- Limpar backups antigos

---

## 📊 Logs e Monitoramento

### Ver Logs

```bash
# Log de backups
tail -f /backup/database/backup.log

# Últimas 50 linhas
tail -50 /backup/database/backup.log

# Buscar erros
grep "❌" /backup/database/backup.log
```

### Formato do Log

```
[2026-02-17 03:00:15] ✅ Backup criado: backup_2026-02-17_03-00-00.sql.gz (4.2 MB)
[2026-02-17 03:00:20] ✅ Enviado para Google Drive: backup_2026-02-17_03-00-00.sql.gz
[2026-02-17 03:00:25] 🧹 Limpeza: 1 backup(s) removido(s)
[2026-02-17 04:00:10] ✅ Backup de arquivos: files_2026-02-17.tar.gz (125 MB, 1234 arquivos)
[2026-02-17 09:00:05] 📧 Relatório semanal enviado para contato@faroldeluz.ong.br
```

---

## 🔒 Segurança

### Proteger Diretório de Backup

Adicione `.htaccess` em `/backup/`:

```apache
# Bloquear acesso web
Order Deny,Allow
Deny from all
```

### Proteger Credenciais

- ✅ Nunca commitar senhas no Git
- ✅ Usar variáveis de ambiente quando possível
- ✅ Refresh Token do Google é criptografado
- ✅ Senha SMTP armazenada no banco

---

## 📝 Checklist de Implementação

- [ ] Executar migration 021
- [ ] Configurar SMTP no admin
- [ ] Testar envio de email
- [ ] Criar projeto no Google Cloud
- [ ] Ativar Google Drive API
- [ ] Criar credenciais OAuth
- [ ] Configurar Google Drive no admin
- [ ] Autenticar com Google
- [ ] Configurar cron jobs
- [ ] Testar backup manual
- [ ] Verificar logs
- [ ] Aguardar primeiro backup automático
- [ ] Verificar Google Drive
- [ ] Aguardar relatório semanal

---

## 🆘 Suporte

**Problemas?**
- Verifique os logs primeiro
- Teste scripts manualmente
- Verifique permissões
- Consulte esta documentação

**Desenvolvido com ❤️ por Thiago Mourão**  
**Instagram:** [@mouraoeguerin](https://www.instagram.com/mouraoeguerin/)
