# 🌟 Farol de Luz

**A Luz do Consolador para os dias de hoje!**

Projeto Espírita que oferece conteúdo espiritual através de revista digital, estudos, diálogos e blog.

---

## 📋 Sobre o Projeto

O **Farol de Luz** é uma plataforma web desenvolvida para disseminar conhecimento espírita através de:

- 📖 **Revista Espírita Digital** - Publicações mensais em formato digital
- 🎙️ **Diálogos do Farol** - Conversas e reflexões espirituais
- 📚 **Estudos Rajian** - Grupo de estudos aprofundados
- ✍️ **Blog** - Artigos e reflexões sobre espiritualidade
- 📧 **Newsletter** - Inscrição para receber conteúdos

---

## 🛠️ Tecnologias Utilizadas

### Backend
- **PHP 7.4+** - Linguagem principal
- **MySQL** - Banco de dados
- **PDO** - Conexão com banco de dados
- **MVC Pattern** - Arquitetura do projeto

### Frontend
- **HTML5** - Estrutura
- **TailwindCSS** - Framework CSS
- **JavaScript Vanilla** - Interatividade
- **TinyMCE** - Editor WYSIWYG avançado

### Bibliotecas e Recursos
- **Particles.js** - Efeitos visuais de partículas
- **Font Awesome** - Ícones
- **Google Fonts** - Tipografia (Poppins)

---

## 📁 Estrutura do Projeto

```
faroldeluz/
├── assets/                 # Recursos estáticos
│   ├── css/               # Estilos CSS
│   ├── js/                # Scripts JavaScript
│   ├── images/            # Imagens do site
│   ├── fonts/             # Fontes customizadas
│   └── uploads/           # Uploads de usuários
├── controllers/           # Controllers MVC
│   ├── Admin/            # Controllers administrativos
│   ├── BlogController.php
│   ├── DialogoController.php
│   ├── RajianController.php
│   └── ...
├── core/                  # Núcleo do sistema
│   ├── Controller.php    # Classe base Controller
│   ├── Model.php         # Classe base Model
│   ├── Router.php        # Sistema de rotas
│   ├── Database.php      # Conexão com banco
│   └── View.php          # Renderização de views
├── database/              # Banco de dados
│   └── migrations/       # Migrations SQL
├── models/                # Models MVC
│   ├── BlogPost.php
│   ├── DialogoFarol.php
│   ├── RajianStudy.php
│   └── ...
├── views/                 # Views (templates)
│   ├── admin/            # Interface administrativa
│   ├── blog/             # Views do blog
│   ├── dialogos/         # Views dos diálogos
│   ├── rajian/           # Views dos estudos
│   ├── layout/           # Layouts compartilhados
│   └── home.php          # Página inicial
├── .env.example           # Exemplo de configuração
├── .htaccess              # Configuração Apache
├── index.php              # Ponto de entrada
├── router.php             # Router secundário
└── functions.php          # Funções auxiliares
```

---

## 🚀 Instalação

### Pré-requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Apache com mod_rewrite habilitado
- Composer (opcional)

### Passo a Passo

1. **Clone o repositório:**
```bash
git clone https://github.com/MouraoBSB/faroldeluz.git
cd faroldeluz
```

2. **Configure o arquivo .env:**
```bash
cp .env.example .env
```

Edite o `.env` com suas credenciais:
```env
DB_HOST=186.209.113.101
DB_NAME=cemaneto_site_faroldeluz
DB_USER=cemaneto_site_faroldeluz
DB_PASS=sua_senha_aqui
BASE_URL=https://faroldeluz.ong.br
```

3. **Crie o banco de dados:**
```sql
CREATE DATABASE faroldeluz CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

4. **Execute as migrations:**

Importe os arquivos SQL da pasta `database/migrations/` em ordem:
```bash
001_create_users.sql
002_create_settings.sql
003_create_blog_posts.sql
...
018_add_cover_image_to_rajian_studies.sql
```

5. **Configure permissões:**
```bash
chmod 755 assets/uploads/
chmod 755 assets/uploads/blog/
chmod 755 assets/uploads/dialogos/
chmod 755 assets/uploads/rajian/
```

6. **Acesse o site:**
- Frontend: `https://faroldeluz.ong.br`
- Admin: `https://faroldeluz.ong.br/admin`
  - Usuário: `admin`
  - Senha: `admin123` (altere imediatamente!)

---

## 📚 Documentação

### TinyMCE - Editor Avançado

O projeto utiliza o **TinyMCE** como editor WYSIWYG. Para documentação completa, consulte:

📖 **[TINYMCE.md](./docs/TINYMCE.md)** - Guia completo de uso e customização

### Outros Guias

- 📘 [DEPLOY.md](./DEPLOY.md) - Guia de deploy em produção
- 🔧 [API.md](./docs/API.md) - Documentação da API (em desenvolvimento)

---

## 🎨 Paleta de Cores

O projeto utiliza uma paleta de cores temática:

```css
--azul-noite: #0A0E27
--azul-cosmico: #1A1F3A
--azul-medio: #2D3561
--azul-turquesa: #4ECDC4
--dourado-luz: #FFD700
--dourado-intenso: #FFA500
--cinza-azulado: #A0AEC0
```

---

## 🔐 Segurança

- ✅ Sanitização de inputs
- ✅ Prepared statements (PDO)
- ✅ Proteção contra SQL Injection
- ✅ Proteção contra XSS
- ✅ CSRF tokens (em desenvolvimento)
- ✅ Senhas com hash bcrypt
- ✅ Sessões seguras

---

## 🤝 Contribuindo

Contribuições são bem-vindas! Para contribuir:

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona MinhaFeature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

---

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

## 👨‍💻 Autor

**Thiago Mourão**
- Instagram: [@mouraoeguerin](https://www.instagram.com/mouraoeguerin/)
- GitHub: [@MouraoBSB](https://github.com/MouraoBSB)

---

## 🌟 Agradecimentos

- Comunidade Espírita
- Todos os colaboradores do projeto
- Usuários e leitores do Farol de Luz

---

## 📞 Suporte

Para suporte, entre em contato através do site [faroldeluz.ong.br](https://faroldeluz.ong.br/contato)

---

**✨ Que a luz do conhecimento espiritual ilumine seu caminho! ✨**
