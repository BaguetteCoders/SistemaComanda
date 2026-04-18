<?php
require_once("DataBase/conexao.php");


function RequestUser($Id, $What, $conn)
{
    $RequestUser = $conn->prepare("SELECT $What from usuarios where id = $Id");
    $RequestUser->execute();

    $row = $RequestUser->fetch(mode: PDO::FETCH_ASSOC);
    return $row[$What];
}

function LoginVerify($name, $password, $conn)
{
    $LoginVerify = $conn->prepare("SELECT id FROM usuarios WHERE nome = :nome AND senha = :senha");
    $LoginVerify->bindvalue(":nome", $name);
    $LoginVerify->bindValue(":senha", $password);
    $LoginVerify->execute();

    $row = $LoginVerify->fetch(mode: PDO::FETCH_ASSOC);
    return $row['id'];
}

function CriarComanda(string $name, $conn): void
{
    $sql = "INSERT INTO comandas (id, nome, status, data_criacao) VALUES (NULL, '$name', 'aberta', NOW())";
    $CriarComanda = $conn->prepare($sql);

    $CriarComanda->execute();
}

function EditarProduto(int $id, string $nome, float $preco, string $categoria, $conn): void
{
    $sql = "UPDATE produtos SET nome = :nome, preco = :preco, categoria = :categoria WHERE id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":nome", $nome);
    $stmt->bindValue(":preco", $preco);
    $stmt->bindValue(":categoria", $categoria);
    $stmt->bindValue(":id", $id);

    $stmt->execute();
}

function CriarProduto(string $nome, float $preco, string $categoria, $conn): void
{

    $sql = "INSERT INTO produtos (id, nome, preco, categoria) VALUES (NULL, :nome, :preco, :categoria)";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":nome", $nome);
    $stmt->bindValue(":preco", $preco);
    $stmt->bindValue(":categoria", $categoria);

    $stmt->execute();
}

function ExcluirProduto($id, $conn)
{
    try {
        $sql = "DELETE FROM produtos WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage());
    }
}
function RequestComandaProdutos($idComanda, $conn)
{
    $sql = "SELECT pedidos from comandas where id = $idComanda";
    $RCP = $conn->prepare($sql);
    $RCP->execute();

    $row = $RCP->fetch(PDO::FETCH_ASSOC);
    return $row['pedidos'];
}
function AdicionarProduto($idComanda, $idProduto, $quantidade, $conn)
{
    for ($i = 0; $i != $quantidade; $i++) {
        $oldProdutos = RequestComandaProdutos($idComanda, $conn);
        $oldProdutos = explode(" ", $oldProdutos);
        array_push($oldProdutos, $idProduto);

        $newProdutos = implode(" ", $oldProdutos);

        $sql = "UPDATE comandas SET pedidos = '$newProdutos' Where id = $idComanda";

        $AdicionarProduto = $conn->prepare($sql);

        $AdicionarProduto->execute();
    }
    return true;
}
function AdicionarObservacao($obs, $idcomanda, $conn)
{
    $sql = "INSERT INTO observacoes(id,idcomanda,observacao) Values(null,$idcomanda,'$obs')";
    $AdcObs = $conn->prepare($sql);
    $AdcObs->execute();
}

function RemoverProdutoDaComanda($comandaId, $produtoId, $conn)
{
    $sql = "SELECT pedidos FROM comandas WHERE id = :id";
    $removercomanda = $conn->prepare($sql);
    $removercomanda->bindValue(":id", $comandaId);
    $removercomanda->execute();

    $row = $removercomanda->fetch(PDO::FETCH_ASSOC);

    $pedidos = explode(" ", $row['pedidos']);

    $pedidos = array_filter($pedidos, function ($p) use ($produtoId) {
        return $p != $produtoId;
    });

    $novaLista = implode(" ", $pedidos);

    $sql = "UPDATE comandas SET pedidos = :pedidos WHERE id = :id";
    $alterarpedidos = $conn->prepare($sql);
    $alterarpedidos->bindValue(":pedidos", $novaLista);
    $alterarpedidos->bindValue(":id", $comandaId);
    $alterarpedidos->execute();
}

?>