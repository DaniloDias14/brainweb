<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="chamado.css">
    <title>Chamado</title>
</head>
<body>
    <div class="container">
        <div class="chamado-numero">
            <h2>Número do Chamado: <span id="numero-chamado">12345</span></h2>
        </div>
        <h1>Detalhes do Chamado</h1>
        <div class="info">
            <div class="info-item">
                <label for="setor">Setor:</label>
                <span id="setor">TI</span>
            </div>
            <div class="info-item">
                <label for="nome">Nome:</label>
                <span id="nome">João da Silva</span>
            </div>
            <div class="info-item">
                <label for="telefone">Telefone:</label>
                <span id="telefone">(11) 98765-4321</span>
            </div>
        </div>
        <div class="descricao">
            <h2>Descrição</h2>
            <p>Aqui vai a descrição do chamado.</p>
        </div>
        <div class="comentarios-anexos">
            <div class="comentarios">
                <h3>Comentários</h3>
                <textarea placeholder="Adicionar novo comentário..."></textarea>
                <button>Adicionar Comentário</button>
            </div>
            <div class="anexos">
                <h3>Anexos</h3>
                <input type="file" id="anexo" />
                <button>Adicionar Anexo</button>
            </div>
        </div>
    </div>
</body>
</html>