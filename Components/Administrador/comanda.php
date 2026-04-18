<?php

$id = $_GET['id'];

$sql = "SELECT * FROM comandas WHERE id = :i";
$stmt = $conn->prepare($sql);
$stmt->bindValue(":i", $id);
$stmt->execute();

$comanda = $stmt->fetch(PDO::FETCH_ASSOC);
?>


<head>
    <title>Comanda</title>
</head>

<body>

    <h1>Comanda:
        <?= $comanda['nome'] ?>
    </h1>
    <p>ID:
        <?= $comanda['id'] ?>
    </p>

    <a href="Index.php">Voltar</a>

</body>