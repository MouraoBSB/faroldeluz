# 🚀 Guia de Deploy - Farol de Luz

## 📋 Informações do Servidor

- **Hospedagem:** Napoleon Host
- **Domínio:** faroldeluz.ong.br
- **IP:** 186.209.113.101
- **Servidor FTP:** ftp.cemanet.org.br
- **Porta FTP:** 21
- **Usuário FTP:** wind_ftp@faroldeluz.ong.br
- **Diretório Remoto:** /home/cemaneto/faroldeluz.ong.br

---

## 🔧 Método 1: Deploy Automatizado (Recomendado)

### Pré-requisitos:
1. **WinSCP** instalado
   - Download: https://winscp.net/eng/download.php

### Passos:
1. Execute o arquivo `deploy.bat`
2. Aguarde a sincronização
3. Acesse https://faroldeluz.ong.br

### O que o script faz:
- ✅ Sincroniza todos os arquivos PHP
- ✅ Envia assets (CSS, JS, imagens)
- ✅ Mantém estrutura de pastas
- ❌ Exclui: `.git/`, `node_modules/`, `.env` local, logs

---

## 📁 Método 2: Deploy Manual via FileZilla

### Pré-requisitos:
1. **FileZilla** instalado
   - Download: https://filezilla-project.org/

### Configuração FileZilla:
1. Abra FileZilla
2. Vá em **Arquivo → Gerenciador de Sites**
3. Clique em **Novo Site**
4. Configure:
   - **Protocolo:** FTP
   - **Host:** ftp.cemanet.org.br
   - **Porta:** 21
   - **Tipo de Logon:** Normal
   - **Usuário:** wind_ftp@faroldeluz.ong.br
   - **Senha:** Fyj3P7w-Dvh6N
5. Clique em **Conectar**

### Arquivos para enviar:
```
✅ /assets/          (CSS, JS, imagens, fontes)
✅ /controllers/     (Todos os controllers)
✅ /core/            (Sistema base)
✅ /database/        (Migrations)
✅ /models/          (Todos os models)
✅ /views/           (Todas as views)
✅ .htaccess
✅ index.php
✅ router.php
✅ functions.php
✅ .env              (CONFIGURAR NO SERVIDOR)

❌ NÃO ENVIAR:
❌ .git/
❌ node_modules/
❌ Material de apoio/
❌ *.log
❌ *.tmp
❌ deploy.bat
❌ .ftpconfig
```

### Diretório de destino:
`/home/cemaneto/faroldeluz.ong.br`

---

## ⚙️ Configuração do Servidor

### 1. Configurar arquivo .env no servidor

Após fazer upload, edite o arquivo `.env` no servidor com as credenciais corretas:

```env
DB_HOST=localhost
DB_NAME=cemaneto_site_faroldeluz
DB_USER=cemaneto_site_faroldeluz
DB_PASS=EDM7avc8cax!gfw*qjp

BASE_URL=https://faroldeluz.ong.br

SESSION_NAME=farol_admin_session
SESSION_LIFETIME=7200

ADMIN_EMAIL=contato@faroldeluz.ong.br
```

### 2. Criar banco de dados

Via **cPanel** ou **phpMyAdmin**:

1. Acesse o painel de controle da hospedagem
2. Vá em **MySQL Databases**
3. Crie um novo banco: `cemaneto_site_faroldeluz`
4. Crie um usuário: `cemaneto_user_farol`
5. Associe o usuário ao banco com todas as permissões

### 3. Importar estrutura do banco

Execute as migrations na ordem:

```sql
-- Via phpMyAdmin, execute cada arquivo em ordem:
001_create_users.sql
002_create_settings.sql
003_create_blog_posts.sql
004_create_rajian_studies.sql
005_create_dialogos.sql
006_create_taxonomy_terms.sql
007_create_taxonomy_relations.sql
008_create_newsletter_subscribers.sql
...
018_add_cover_image_to_rajian_studies.sql
```

### 4. Verificar permissões de pastas

Certifique-se que as pastas têm permissão de escrita:

```
/assets/uploads/           (755 ou 777)
/assets/uploads/blog/      (755 ou 777)
/assets/uploads/dialogos/  (755 ou 777)
/assets/uploads/rajian/    (755 ou 777)
```

### 5. Configurar .htaccess

O arquivo `.htaccess` já está configurado para:
- ✅ Rewrite URLs amigáveis
- ✅ Redirecionar para HTTPS
- ✅ Bloquear acesso a arquivos sensíveis

---

## 🔐 Segurança

### Após o deploy:

1. **Altere a senha do admin:**
   - Acesse: https://faroldeluz.ong.br/admin
   - Login: admin
   - Senha padrão: admin123
   - **MUDE IMEDIATAMENTE!**

2. **Configure SSL/HTTPS:**
   - Solicite certificado SSL gratuito no painel da Napoleon Host
   - Ou use Let's Encrypt

3. **Backup regular:**
   - Configure backup automático no cPanel
   - Faça backup do banco de dados semanalmente

---

## 🧪 Checklist Pós-Deploy

- [ ] Site acessível em https://faroldeluz.ong.br
- [ ] Página inicial carrega corretamente
- [ ] Admin acessível em /admin
- [ ] Login funcionando
- [ ] Upload de imagens funcionando
- [ ] Blog posts aparecem
- [ ] Estudos Rajian aparecem
- [ ] Diálogos do Farol aparecem
- [ ] Newsletter funcionando
- [ ] Formulário de contato funcionando
- [ ] Redes sociais linkadas
- [ ] SSL/HTTPS ativo
- [ ] Senha do admin alterada

---

## 🆘 Solução de Problemas

### Erro 500 - Internal Server Error
- Verifique permissões do `.htaccess`
- Verifique logs de erro do PHP no cPanel
- Confirme versão do PHP (7.4+)

### Erro de conexão com banco
- Verifique credenciais no `.env`
- Confirme que o banco foi criado
- Verifique se o usuário tem permissões

### Imagens não aparecem
- Verifique permissões das pastas `/assets/uploads/`
- Confirme que as imagens foram enviadas

### CSS/JS não carrega
- Limpe cache do navegador
- Verifique se a pasta `/assets/` foi enviada completa
- Confirme `BASE_URL` no `.env`

---

## 📞 Suporte

**Desenvolvedor:** Thiago Mourão  
**Instagram:** @mouraoeguerin  
**Data:** 2026-02-17

---

## 🔄 Atualizações Futuras

Para atualizar o site após mudanças:

1. Execute `deploy.bat` novamente
2. Ou envie apenas os arquivos modificados via FTP
3. Se houver mudanças no banco, execute as novas migrations

---

**✨ Boa sorte com o deploy!**
