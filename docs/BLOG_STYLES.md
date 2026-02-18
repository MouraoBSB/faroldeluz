# 📝 Estilos Customizados do Blog

**Autor:** Thiago Mourão  
**Data:** 2026-02-17  
**Versão:** 1.0

---

## 📋 Índice

1. [Visão Geral](#visão-geral)
2. [Variáveis CSS Customizáveis](#variáveis-css-customizáveis)
3. [Elementos Estilizados](#elementos-estilizados)
4. [Como Usar](#como-usar)
5. [Customização](#customização)
6. [Responsividade](#responsividade)
7. [Exemplos de Uso](#exemplos-de-uso)

---

## 🎯 Visão Geral

Os posts do blog utilizam um sistema de estilos customizados baseado no padrão WordPress, adaptado para o tema visual do Farol de Luz (cores azul/dourado).

**Arquivo:** `views/blog/single.php`  
**Classe principal:** `.post-content`

Todos os elementos HTML dentro da classe `.post-content` recebem estilização automática e consistente.

---

## 🎨 Variáveis CSS Customizáveis

Todas as cores, espaçamentos e tamanhos podem ser editados facilmente no topo do CSS:

```css
:root {
  /* Cores de Links */
  --color-link: #4A9EFF;              /* Azul claro */
  --color-link-hover: #06BCC1;        /* Turquesa */
  
  /* Citações (Blockquotes) */
  --color-quote-border: #E8B86D;      /* Dourado */
  --width-quote-border: 3px;
  --bg-quote: rgba(232, 184, 109, 0.1); /* Dourado translúcido */
  
  /* Blocos de Código */
  --color-code-text: #89E3E4;         /* Ciano */
  --bg-code: #0B0515;                 /* Preto azulado */

  /* Espaçamentos */
  --space-s: 0.25rem;    /* Pequeno */
  --space-m: 0.75rem;    /* Médio */
  --space-l: 1.25rem;    /* Grande */
  --space-xl: 2.25rem;   /* Extra Grande */
  --space-xxl: 2.5rem;   /* Extra Extra Grande */
  
  /* Bordas */
  --radius-m: 0.75rem;   /* Arredondamento médio */
  
  /* Transições */
  --transition-default: 0.2s ease-in-out;

  /* Tipografia */
  --font-weight-light: 300;
  --font-weight-regular: 400;
  --font-weight-medium: 500;
  --font-weight-bold: 700;
  
  --font-size-xs: .85rem;
  --font-size-p: 1.15rem;
  --font-size-m: 1.5rem;
  --font-size-l: 2rem;
  
  /* Tamanhos Mobile */
  --mobile-font-size-p: 1.15rem;
  --mobile-font-size-m: 1.5rem;
  --mobile-font-size-l: 2rem;
  
  /* Line Heights */
  --line-height-body: 1.75em;
  --line-height-heading: 1.25em;
  --line-height-list: 1.2em;
}
```

---

## 📐 Elementos Estilizados

### 1. **Títulos (H2-H6)**

```html
<h2>Título Principal</h2>
<h3>Subtítulo</h3>
```

**Características:**
- Cor: Dourado (`#E8B86D`)
- H2: 2rem (desktop), responsivo no mobile
- H3-H6: 1.5rem (desktop), responsivo no mobile
- Espaçamento superior: 2.5rem
- Espaçamento inferior: 0.25rem
- Peso: Bold (700)

---

### 2. **Parágrafos**

```html
<p>Texto do parágrafo com conteúdo.</p>
```

**Características:**
- Cor: Cinza claro (`#B8C5D6`)
- Tamanho: 1.15rem
- Line-height: 1.75em
- Espaçamento inferior: 1.25rem

---

### 3. **Links**

```html
<p>Texto com <a href="#">link</a> dentro.</p>
```

**Características:**
- Cor: Azul (`#4A9EFF`)
- Hover: Turquesa (`#06BCC1`)
- Sublinhado
- Peso: Medium (500)
- Transição suave (0.2s)

---

### 4. **Listas (UL/OL)**

```html
<ul>
  <li>Item 1</li>
  <li>Item 2</li>
  <li>Item 3</li>
</ul>
```

**Características:**
- Cor: Cinza claro (`#B8C5D6`)
- Tamanho: 1.15rem
- Peso: Medium (500)
- Espaçamento entre itens: 1.25rem
- Padding esquerdo: 2rem

---

### 5. **Imagens**

```html
<img src="imagem.jpg" alt="Descrição">
```

**Características:**
- Largura máxima: 100%
- Altura: Automática
- Bordas arredondadas: 0.75rem
- Borda: 1px sólida (`#2A3F5F`)
- Margem vertical: 2.5rem

---

### 6. **Citações (Blockquotes)**

```html
<blockquote>
  <p>Texto da citação importante.</p>
  <cite>Autor da citação</cite>
</blockquote>
```

**Características:**
- Borda esquerda: 3px dourada (`#E8B86D`)
- Fundo: Dourado translúcido
- Padding: 0.75rem 1.25rem
- Bordas arredondadas à direita
- Margem vertical: 2.5rem
- Cor do texto: Cinza claro (`#B8C5D6`)
- Cor da citação (cite): Cinza médio (`#8FA3C1`)

---

### 7. **Blocos de Código**

```html
<pre><code>
function exemplo() {
  return "código";
}
</code></pre>
```

**Características:**
- Fundo: Preto azulado (`#0B0515`)
- Cor do texto: Ciano (`#89E3E4`)
- Padding: 2.25rem
- Bordas arredondadas: 0.75rem
- Scroll horizontal automático
- Margem vertical: 2.5rem
- Fonte: Courier New, monospace

**Código inline:**
```html
<p>Use a função <code>exemplo()</code> aqui.</p>
```
- Fundo escuro
- Padding: 0.2em 0.4em
- Bordas arredondadas pequenas

---

### 8. **Tabelas**

```html
<table>
  <thead>
    <tr>
      <th>Coluna 1</th>
      <th>Coluna 2</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Dado 1</td>
      <td>Dado 2</td>
    </tr>
  </tbody>
</table>
```

**Características:**
- Largura: 100%
- Bordas: 1px sólida (`#2A3F5F`)
- Cabeçalho (th):
  - Fundo: `#1A2332`
  - Cor: Dourado (`#E8B86D`)
  - Peso: Bold
- Células (td):
  - Cor: Cinza claro (`#B8C5D6`)
- Padding: 0.75rem
- Margem vertical: 2.5rem

---

### 9. **Colunas (TinyMCE)**

**2 Colunas:**
```html
<div class="row-2cols">
  <div class="col">Conteúdo coluna 1</div>
  <div class="col">Conteúdo coluna 2</div>
</div>
```

**3 Colunas:**
```html
<div class="row-3cols">
  <div class="col">Coluna 1</div>
  <div class="col">Coluna 2</div>
  <div class="col">Coluna 3</div>
</div>
```

**Características:**
- Display: Flex
- Gap: 20px (2 colunas) / 15px (3 colunas)
- Flex-wrap: Sim
- Mobile: Empilha verticalmente
- Largura mínima: 250px (2 cols) / 200px (3 cols)

---

## 🚀 Como Usar

### No TinyMCE (Admin)

Ao criar ou editar posts no admin, simplesmente use os elementos HTML normalmente:

1. **Títulos:** Use os botões H2, H3, etc.
2. **Parágrafos:** Digite normalmente
3. **Links:** Selecione texto e clique no ícone de link
4. **Listas:** Use os botões de lista numerada/não numerada
5. **Imagens:** Insira imagens normalmente
6. **Citações:** Use o botão de blockquote
7. **Código:** Use o botão de código ou `<code>`

### No Frontend

Todos os posts são automaticamente renderizados com a classe `.post-content`:

```php
<div class="post-content mb-12">
    <?= $post['content_html'] ?>
</div>
```

**Não é necessário adicionar classes manualmente!**

---

## 🎨 Customização

### Alterar Cores

Edite as variáveis CSS no arquivo `views/blog/single.php`:

```css
:root {
  --color-link: #FF5733;        /* Nova cor de link */
  --color-link-hover: #C70039;  /* Nova cor de hover */
}
```

### Alterar Espaçamentos

```css
:root {
  --space-l: 2rem;    /* Aumentar espaçamento entre parágrafos */
  --space-xxl: 3rem;  /* Aumentar espaçamento antes de títulos */
}
```

### Alterar Tamanhos de Fonte

```css
:root {
  --font-size-p: 1.25rem;  /* Parágrafos maiores */
  --font-size-l: 2.5rem;   /* H2 maior */
}
```

---

## 📱 Responsividade

### Breakpoint Mobile

`@media screen and (max-width: 767px)`

**Ajustes automáticos:**
- Tamanhos de fonte reduzidos conforme variáveis mobile
- Colunas empilham verticalmente
- Espaçamentos mantidos proporcionais

**Exemplo:**
```css
@media screen and (max-width: 767px) {
  .post-content h2 {
    font-size: var(--mobile-font-size-l);  /* 2rem */
  }
  
  .post-content .row-2cols { 
    flex-direction: column;  /* Empilha colunas */
  }
}
```

---

## 💡 Exemplos de Uso

### Post Completo

```html
<div class="post-content">
  <h2>Introdução ao Espiritismo</h2>
  
  <p>O Espiritismo é uma doutrina que estuda a natureza, origem e destino dos Espíritos, bem como suas relações com o mundo corporal. Saiba mais em <a href="#">nosso guia completo</a>.</p>
  
  <h3>Principais Conceitos</h3>
  
  <ul>
    <li>Imortalidade da alma</li>
    <li>Reencarnação</li>
    <li>Lei de causa e efeito</li>
  </ul>
  
  <blockquote>
    <p>Nascer, morrer, renascer ainda e progredir sempre, tal é a lei.</p>
    <cite>Allan Kardec</cite>
  </blockquote>
  
  <h3>Obras Básicas</h3>
  
  <div class="row-2cols">
    <div class="col">
      <img src="livro1.jpg" alt="O Livro dos Espíritos">
      <p>O Livro dos Espíritos</p>
    </div>
    <div class="col">
      <img src="livro2.jpg" alt="O Evangelho Segundo o Espiritismo">
      <p>O Evangelho Segundo o Espiritismo</p>
    </div>
  </div>
  
  <p>Para estudar mais, use o código <code>ESTUDO2026</code> em nossa plataforma.</p>
</div>
```

---

## 🔧 Manutenção

### Localização dos Estilos

**Arquivo:** `views/blog/single.php`  
**Linhas:** 114-336 (aproximadamente)

### Backup

Antes de fazer alterações significativas, faça backup do arquivo:

```bash
cp views/blog/single.php views/blog/single.php.backup
```

### Testes

Após modificações, teste em:
1. Desktop (Chrome, Firefox, Safari)
2. Mobile (responsividade)
3. Diferentes tipos de conteúdo (imagens, tabelas, código)

---

## 📚 Referências

- **Baseado em:** Padrão WordPress de estilização de posts
- **Tema:** Farol de Luz (azul/dourado)
- **Framework CSS:** Tailwind CSS (para layout geral)
- **Compatibilidade:** Todos os navegadores modernos

---

## 🆘 Troubleshooting

### Problema: Estilos não aplicados

**Solução:**
1. Verifique se a div tem a classe `.post-content`
2. Limpe o cache do navegador
3. Verifique se o arquivo foi atualizado no servidor

### Problema: Cores não aparecem

**Solução:**
1. Verifique se as variáveis CSS estão definidas
2. Confirme que não há CSS conflitante
3. Use DevTools do navegador para inspecionar

### Problema: Responsividade quebrada

**Solução:**
1. Verifique o media query `@media screen and (max-width: 767px)`
2. Teste em diferentes tamanhos de tela
3. Confirme que flex-direction está correto

---

## 📝 Changelog

### v1.0 (2026-02-17)
- ✅ Implementação inicial dos estilos customizados
- ✅ Variáveis CSS para fácil customização
- ✅ Suporte completo a elementos HTML
- ✅ Responsividade mobile
- ✅ Suporte a colunas TinyMCE
- ✅ Documentação completa

---

**Desenvolvido com ❤️ por Thiago Mourão**  
**Instagram:** [@mouraoeguerin](https://www.instagram.com/mouraoeguerin/)
