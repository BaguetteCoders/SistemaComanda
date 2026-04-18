<?php

$id = $_GET['id'];

$sql = "SELECT * FROM comandas WHERE id = :i";
$stmt = $conn->prepare($sql);
$stmt->bindValue(":i", $id);
$stmt->execute();

$comanda = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_REQUEST['Criar'])) {
    $nome = $_REQUEST['nome'];
    try {
        CriarComanda(name: $nome, conn: $conn);
        header(header: "Refresh: 0");
    } catch (PDOException $e) {
        echo $e;
    }
}

if (isset($_POST['EditarItem'])) {

    $produtoId = $_POST['item_id'];
    $novaQtd = $_POST['nova_qtd'];
    $idComanda = $_GET['id'];

    RemoverProdutoDaComanda($idComanda, $produtoId, $conn);

    AdicionarProduto($idComanda, $produtoId, $novaQtd, $conn);

    echo "<script>location.href='index.php?id=$idComanda'</script>";
}

if (isset($_POST['ExcluirItem'])) {

    $produtoId = $_POST['item_id'];
    $idComanda = $_GET['id'];

    RemoverProdutoDaComanda($idComanda, $produtoId, $conn);

    echo "<script>location.href='index.php?id=$idComanda'</script>";
}

$sqlCategorias = "SELECT DISTINCT categoria FROM produtos WHERE categoria IS NOT NULL AND categoria != '' ORDER BY categoria ASC";
$resultCategorias = $conn->query($sqlCategorias);

?>


<head>
    <title>Comanda</title>
</head>

<body>

    <div class="top-bar">
        <a href="Index.php" class="btn-voltar">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="comanda-atendente">
        <label class="nome-cliente">Nome:</label>
        <div class="caixa-cliente">
            <?= $comanda['nome'] ?>
        </div>
    </div>

    <div class="alinhar-painel">
        <div class="painel-box-atendente d-flex flex-column">
            <div class="painel-header-atendente">
                <h2>Produtos</h2>
                <div class="busca-wrapper">
                    <i class="fas fa-search busca-icon"></i> <input type="text" id="inputBusca" class="busca-input"
                        placeholder="O que você está procurando?" onkeyup="filtrarProdutos()">
                </div>

                <div class="categorias-container-atendente">
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
                    <?php
                    try {
                        $sql = "SELECT * FROM produtos WHERE ativo = 1";
                        $RequestUser = $conn->prepare($sql);
                        $RequestUser->execute();
                    } catch (PDOException $e) {
                        print ($e);
                    }
                    while ($row = $RequestUser->fetch(PDO::FETCH_ASSOC)):
                        $id = $row['id'];
                        $produto = $row['nome'];
                        $preco = $row['preco'];
                        $categoria = $row['categoria'];
                        ?>
                        <div class="card-produto" data-id="<?php echo (string) $id ?>"
                            data-nome="<?php echo (string) $produto ?>" data-preco="<?php echo (string) $preco ?>"
                            data-categoria="<?php echo (string) $categoria ?>" data-bs-toggle="modal"
                            data-bs-target="#modalAdicionarProduto">

                            <div class="nome-produto">
                                <?php echo "$produto" ?>
                            </div>

                            <div class="preco-produto">
                                <?php echo "R$ $preco" ?>
                            </div>

                        </div>
                    <?php endwhile ?>
                </div>
            </div>
        </div>

        <div class="painel-box-atendente carrinho-selecionados d-flex flex-column">
            <div class="painel-header-atendente mb-3">
                <h2>Itens na Comanda</h2>

                <div id="listaProdutosAdicionados" class="container-produtos">
                </div>

            </div>
            <div class="container-produtos">
                <?php

                $Pedidos = RequestComandaProdutos($_GET['id'], $conn);
                $Pedidos = explode(" ", $Pedidos);
                sort($Pedidos);

                $TotalPedidos = count($Pedidos);
                $TotalPreco = 0;
                $EchoList = [];

                for ($i = 1; $i < $TotalPedidos; $i++) {

                    $SingleProduto = (int) $Pedidos[$i];

                    $sql = "SELECT * FROM produtos WHERE id = :id";
                    $SelectProdutos = $conn->prepare($sql);
                    $SelectProdutos->bindValue(":id", $SingleProduto);
                    $SelectProdutos->execute();

                    $row = $SelectProdutos->fetch(PDO::FETCH_ASSOC);

                    $nome = $row['nome'];
                    $precoUnit = $row['preco'];
                    $total = count(array_keys($Pedidos, $SingleProduto));

                    $precoTotal = $precoUnit * $total;

                    if (!in_array($SingleProduto, $EchoList)):
                        ?>

                        <div class="card-produto item-comanda" data-id="<?= $SingleProduto ?>" data-nome="<?= $nome ?>"
                            data-quantidade="<?= $total ?>" data-bs-toggle="modal" data-bs-target="#modalEditarItem">

                            <div class="nome-produto">
                                <?= $nome ?>
                            </div>

                            Total: <?= $total ?>

                            <div class="preco-produto">
                                R$ <?= number_format($precoTotal, 2, ',', '.') ?>
                            </div>

                        </div>

                        <?php
                        $TotalPreco += $precoTotal;
                        $EchoList[] = $SingleProduto;
                    endif;
                }
                ?>
            </div>
            <div class="total-comanda">
                <span>Total:</span>
                <span id="valorTotal">R$ <?php echo "$TotalPreco" ?></span>
            </div>

            <div>

            </div>
        </div>

        <div class="painel-box-atendente d-flex flex-column">

            <div class="painel-header-atendente">
                <form method="POST" class="painel-header-atendente">
                    <h2>Observações</h2>

                    <div class="observacao-group">
                        <div class="observacao-wrapper">
                            <i class="fa-solid fa-pen lapis-icon"></i>
                            <input type="text" name="Observacao" class="observacao-input"
                                placeholder="Adicione uma observação.">
                        </div>

                        <button type="submit" name="AdicionarObservacao" class="btn-adicionar">+</button>
                    </div>
                </form>
            </div>

            <div class="container-produtos">

                <?php
                $sql = "SELECT * FROM observacoes WHERE idComanda = :id ORDER BY id DESC";
                $observacao = $conn->prepare($sql);
                $observacao->bindValue(":id", $_GET['id']);
                $observacao->execute();

                while ($obs = $observacao->fetch(PDO::FETCH_ASSOC)):
                    ?>
                    <div class="card-produto">
                        <div class="nome-produto">
                            <?= $obs['observacao'] ?>
                        </div>
                    </div>
                <?php endwhile; ?>

            </div>

        </div>
    </div>

    </div>


    <div class="modal fade" id="modalAdicionarProduto">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST">

                    <div class="modal-header">
                        <h5 class="modal-title">Deseja adicionar?</h5>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="edit_id" id="edit-id">

                        <label>Nome:</label>
                        <input readonly type="text" name="edit_nome" id="edit-nome" class="form-control" required>

                        <label>Quantidade</label>
                        <input type="number" name="quantidade" class="form-control" value="1" required>

                        <input readonly type="hidden" step="0.01" name="edit_preco" id="edit-preco" class="form-control"
                            required>

                        <input readonly type="hidden" name="edit_categoria" id="edit-categoria" class="form-control">

                    </div>

                    <div class="modal-footer d-flex justify-content-between">

                        <button type="submit" name="AdicionarProduto" class="btn btn-success">
                            Adicionar à comanda
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarItem">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST">

                    <div class="modal-header">
                        <h5 class="modal-title">Editar Item</h5>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="item_id" id="item-id">

                        <label>Produto</label>
                        <input type="text" id="item-nome" class="form-control" readonly>

                        <label class="mt-2">Quantidade</label>
                        <input type="number" name="nova_qtd" id="item-qtd" class="form-control">

                    </div>

                    <div class="modal-footer d-flex justify-content-between">

                        <button type="submit" name="ExcluirItem" class="btn btn-danger">
                            Excluir
                        </button>

                        <button type="submit" name="EditarItem" class="btn btn-success">
                            Salvar
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
            let cards = document.querySelectorAll('.container-produtos .card-produto:not(.item-comanda)');

            cards.forEach(card => {

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

        document.querySelectorAll('.item-comanda').forEach(item => {
            item.addEventListener('click', () => {

                document.getElementById('item-id').value = item.dataset.id;
                document.getElementById('item-nome').value = item.dataset.nome;
                document.getElementById('item-qtd').value = item.dataset.quantidade;

            });
        });
    </script>
</body>

<?php
if (isset($_REQUEST['AdicionarProduto'])) {
    $id = $_REQUEST['edit_id'];
    $idComanda = $_GET['id'];
    AdicionarProduto($idComanda, $id, $_REQUEST['quantidade'], $conn);
    $script = "<script>location.href='index.php?id=$idComanda'</script>";
    echo $script;
}
if (isset($_REQUEST['AdicionarObservacao'])) {
    $Obs = $_REQUEST['Observacao'];
    $idComanda = $_GET['id'];
    AdicionarObservacao($Obs, $idComanda, $conn);
    $script = "<script>location.href='index.php?id=$idComanda'</script>";
    echo $script;
}
?>