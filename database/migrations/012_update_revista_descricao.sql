-- Atualizar descrição da revista com texto pré-formatado
-- Autor: Thiago Mourão
-- URL: https://www.instagram.com/mouraoeguerin/
-- Data: 2026-02-15 17:43:00

UPDATE settings 
SET setting_value = '<p>A Revista Espírita Farol de Luz é o braço editorial do nosso projeto, funcionando como uma oficina onde o conhecimento é trabalhado com <strong>profundidade</strong>, <strong>carinho</strong> e <strong>fidelidade doutrinárias</strong>. Se o nosso canal no YouTube sinaliza a luz em tempo real, a revista é o registro perene dessa sinalização — um convite à pausa, à leitura reflexiva e ao estudo sistemático.</p>

<p>Cada edição é cuidadosamente planejada para oferecer o que chamamos de <strong>"consolo fundamentado"</strong>: uma mensagem que fala ao coração, mas que se sustenta na lógica inabalável da Codificação de Allan Kardec.</p>

<h3>O que você encontra em nossas páginas?</h3>

<p>Nossa linha editorial busca conectar a sabedoria dos espíritos com as dores e desafios da alma contemporânea. Em cada número, você terá acesso a:</p>

<ul>
<li><strong>Artigos de Fundo:</strong> Estudos aprofundados sobre temas da atualidade sob a ótica espírita.</li>
<li><strong>Janela Psicológica:</strong> Reflexões voltadas à saúde mental e ao autoconhecimento.</li>
<li><strong>Arte e Poesia:</strong> A beleza da mensagem consoladora expressa através da sensibilidade artística.</li>
<li><strong>Vozes dos Espíritos:</strong> Contribuições e relatos que iluminam nossa jornada na Terra.</li>
</ul>

<h3>Leitura Digital e Sustentabilidade</h3>

<p>Acreditamos que o conhecimento espírita deve ser acessível a todos. Por isso, disponibilizamos nossa revista gratuitamente em formato digital (PDF), para que você possa ler em seu computador, tablet ou celular, em qualquer lugar do mundo.</p>

<p>Além da versão digital, o Projeto Farol de Luz produz edições impressas especiais. Ao adquirir uma versão física, você não apenas leva para casa um material de alta qualidade, mas também contribui diretamente para a manutenção de toda a nossa oficina e para a produção de nossos conteúdos audiovisuais.</p>'
WHERE setting_key = 'revista_descricao';
