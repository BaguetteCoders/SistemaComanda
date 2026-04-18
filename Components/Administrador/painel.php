<?php

if (isset($_REQUEST['Criar'])) {
    $nome = $_REQUEST['nome'];
    try {
        CriarComanda(name: $nome, conn: $conn);
        header(header: "Refresh: 0");
    } catch (PDOException $e) {
        echo $e;
    }
}

if (isset($_POST['CriarProduto'])) {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];

    try {
        CriarProduto($nome, $preco, $categoria, $conn);
        header("Refresh: 0");
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

if (isset($_POST['ExcluirProduto'])) {
    $id = $_POST['edit_id'];

    try {
        ExcluirProduto($id, $conn);
        header("Refresh: 0");
    } catch (PDOException $e) {
        echo "Erro ao excluir: " . $e->getMessage();
    }
}

if (isset($_POST['EditarProduto'])) {

    $id = $_POST['edit_id'];
    $nome = $_POST['edit_nome'];
    $preco = $_POST['edit_preco'];
    $categoria = $_POST['edit_categoria'];

    try {
        EditarProduto($id, $nome, $preco, $categoria, $conn);
        header("Refresh: 0");
    } catch (PDOException $e) {
        echo $e;
    }
}

$sql = "SELECT * FROM comandas ORDER BY id DESC";
$result = $conn->query($sql);

$sqlProdutos = "SELECT * FROM produtos ORDER BY nome ASC";
$resultProdutos = $conn->query($sqlProdutos);

$sqlCategorias = "SELECT DISTINCT categoria FROM produtos WHERE categoria IS NOT NULL AND categoria != '' ORDER BY categoria ASC";
$resultCategorias = $conn->query($sqlCategorias);

?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <div class="painel-box">

        <div class="painel-header">
            <h2>Comandas</h2>

            <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#painelComandas">
                ⬇
            </button>
        </div>

        <div class="collapse show" id="painelComandas">
            <div class="container-comandas">

                <div class="card-comanda add-comanda" data-bs-toggle="modal" data-bs-target="#modalComanda">
                    +
                </div>

                <?php while ($row = $result->fetch()): ?>
                    <a href="index.php?id=<?= $row['id'] ?>" class="card-comanda">
                        <strong>#<?= $row['id'] ?></strong>
                        <div class="nome-comanda"><?= $row['nome'] ?></div>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <div class="painel-box">
        <div class="painel-header">
            <h2>Produtos</h2>
            <div class="busca-wrapper">
                <i class="fas fa-search busca-icon"></i> <input type="text" id="inputBusca" class="busca-input"
                    placeholder="O que você está procurando?" onkeyup="filtrarProdutos()">
            </div>

            <div class="categorias-container">
                <button class="btn-categoria active" onclick="filtrarPorCategoria('todos', this)">Tudo</button>
                <?php while ($cat = $resultCategorias->fetch()): ?>
                    <button class="btn-categoria" onclick="filtrarPorCategoria('<?= $cat['categoria'] ?>', this)">
                        <?= ucfirst($cat['categoria']) ?>
                    </button>
                <?php endwhile; ?>
            </div>

            <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#painelProdutos">
                ⬇
            </button>
        </div>

        <div class="collapse show" id="painelProdutos">
            <div class="container-produtos">

                <div class="card-produto add-comanda" data-bs-toggle="modal" data-bs-target="#modalNovoProduto"
                    style="display: flex; justify-content: center; align-items: center; font-size: 2rem; flex: 0 0 175px; height: 140px;">
                    +
                </div>

                <?php while ($produto = $resultProdutos->fetch()): ?>
                    <div class="card-produto" data-id="<?= $produto['id'] ?>" data-nome="<?= $produto['nome'] ?>"
                        data-preco="<?= $produto['preco'] ?>" data-categoria="<?= $produto['categoria'] ?>"
                        data-bs-toggle="modal" data-bs-target="#modalEditarProduto">

                        <div class="nome-produto">
                            <?= $produto['nome'] ?>
                        </div>

                        <div class="preco-produto">
                            R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="modalComanda" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: #2a2a2a; color: white; border: 1px solid #444;">
                <form method="POST">
                    <div class="modal-header" style="border-bottom: 1px solid #444;">
                        <h5 class="modal-title">Abrir Nova Comanda</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>Nome do Cliente / Mesa</label>
                        <input type="text" name="nome" class="form-control bg-dark text-white border-secondary"
                            placeholder="Ex: Mesa 05 ou João" required>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #444;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="Criar" class="btn btn-success">Criar Comanda</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNovoProduto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: #2a2a2a; color: white; border: 1px solid #444;">
                <form method="POST">
                    <div class="modal-header" style="border-bottom: 1px solid #444;">
                        <h5 class="modal-title">Cadastrar Novo Produto</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>Nome do Produto</label>
                        <input type="text" name="nome" class="form-control bg-dark text-white border-secondary"
                            placeholder="Ex: Batata Frita" required>

                        <label class="mt-3">Preço (R$)</label>
                        <input type="number" step="0.01" name="preco"
                            class="form-control bg-dark text-white border-secondary" placeholder="0,00" required>

                        <label class="mt-3">Categoria</label>
                        <input type="text" name="categoria" class="form-control bg-dark text-white border-secondary"
                            placeholder="Ex: Bebidas">
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #444;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="CriarProduto" class="btn btn-success">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarProduto">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST">

                    <div class="modal-header">
                        <h5 class="modal-title">Editar Produto</h5>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="edit_id" id="edit-id">

                        <label>Nome</label>
                        <input type="text" name="edit_nome" id="edit-nome" class="form-control" required>

                        <label class="mt-2">Preço</label>
                        <input type="number" step="0.01" name="edit_preco" id="edit-preco" class="form-control"
                            required>

                        <label class="mt-2">Categoria</label>
                        <input type="text" name="edit_categoria" id="edit-categoria" class="form-control">

                    </div>

                    <div class="modal-footer d-flex justify-content-between">
                        <button type="submit" name="ExcluirProduto" class="btn btn-outline-danger"
                            onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                            <i class="fas fa-trash"></i> Excluir
                        </button>

                        <button type="submit" name="EditarProduto" class="btn btn-success">
                            Salvar Alterações
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.querySelectorAll('.card-produto').forEach(card => {
            card.addEventListener('click', () => {

                document.getElementById('edit-id').value = card.dataset.id;
                document.getElementById('edit-nome').value = card.dataset.nome;
                document.getElementById('edit-preco').value = card.dataset.preco;
                document.getElementById('edit-categoria').value = card.dataset.categoria;

            });
        });

        function filtrarProdutos() {
            let input = document.getElementById('inputBusca').value.toLowerCase();
            let cards = document.querySelectorAll('.card-produto');

            cards.forEach(card => {
                if (card.classList.contains('add-comanda')) return;

                let nomeProduto = card.querySelector('.nome-produto').innerText.toLowerCase();

                if (nomeProduto.includes(input)) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
        }

        let categoriaAtual = 'todos';

        function filtrarPorCategoria(cat, btn) {

            document.querySelectorAll('.btn-categoria').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            categoriaAtual = cat.toLowerCase();
            executarFiltro();
        }

        function filtrarProdutos() {
            executarFiltro();
        }

        function executarFiltro() {
            let texto = document.getElementById('inputBusca').value.toLowerCase();
            let cards = document.querySelectorAll('.card-produto');

            cards.forEach(card => {
                if (card.classList.contains('add-comanda')) return;

                let nome = card.dataset.nome.toLowerCase();
                let categoriaCard = card.dataset.categoria.toLowerCase();

                let bateTexto = nome.includes(texto);
                let bateCategoria = (categoriaAtual === 'todos' || categoriaCard === categoriaAtual);

                if (bateTexto && bateCategoria) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
        }
    </script>
</body>

</html>