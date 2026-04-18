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
?>


<head>
    <title>Comanda</title>
</head>

<body>

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
                    <?php /* while ($cat = $resultCategorias->fetch()): ?>
<button class="btn-categoria" onclick="filtrarPorCategoria('<?= $cat['categoria'] ?>', this)">
<?= ucfirst($cat['categoria']) ?>
</button>
<?php endwhile; */ ?>
                </div>

                <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#painelProdutos">
                    ⬇
                </button>
            </div>

            <div class="collapse show" id="painelProdutos">
                <div class="container-produtos">
                    <?php
                    try {
                        $filtro = "Tudo";
                        if ($filtro != "Tudo" || !isset($filtro)) {
                            $filtro = "and categoria = " . "'$filtro'";
                        } else {
                            $filtro = "";
                        }
                        $sql = "SELECT * from produtos where ativo =   1 $filtro";
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

                for ($i = 1; $i != $TotalPedidos; $i++) {

                    $SingleProduto = (int) $Pedidos[$i];

                    $sql = "SELECT * FROM produtos where id = $SingleProduto";
                    $SelectProdutos = $conn->prepare($sql);
                    $SelectProdutos->execute();

                    $row = $SelectProdutos->fetch(PDO::FETCH_ASSOC);

                    $nome = $row['nome'];
                    $preco = $row['preco'];
                    $total = count(array_keys($Pedidos, $SingleProduto));

                    $preco = $preco * $total;

                    if (!in_array($SingleProduto, $EchoList)) {
                        echo "<div class='card-produto' data-id='' data-nome='' data-preco=''
                    data-categoria='' data-bs-toggle='Modal'
                    data-bs-target='#modalAdicionarProduto'>
                    
                    <div class='nome-produto'>
                    $nome
                    </div>
                    Total: $total
                    <div class='preco-produto'>
                    R$ $preco
                    </div>
                    
                    </div>";
                        $TotalPreco += $preco;
                        array_push($EchoList, $SingleProduto);
                    }
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

        <div class="painel-box-atendente observacao">
            <form method="POST" class="painel-header-atendente">
                <h2>Observações</h2>

                <div class="observacao-wrapper">
                    <i class="fa-solid fa-pen lapis-icon"></i>
                    <input type="text" name="Observacao" id="inputObservacao" class="observacao-input"
                        placeholder="Adicione uma observação." onkeyup="filtrarProdutos()">
                </div>

                <div id="listaObservacoes" class="container-produtos">
                </div>

                <button type="submit" name="AdicionarObservacao" class="btn-adicionar">+</button>
            </form>
        </div>
    </div>

    <div class="alinhar-btn">
        <a href="Index.php" class="btn-finalizar">Finalizar</a>
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
    AdicionarObservacao($Obs,$idComanda,$conn);
    $script = "<script>location.href='index.php?id=$idComanda'</script>";
    echo $script;
}
?>