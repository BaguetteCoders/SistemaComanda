<?php if (isset($_REQUEST['Criar'])) {

    $nome = $_REQUEST['nome'];
    try {
        CriarComanda(name: $nome, conn: $conn);
        header(header: "Refresh: 0");
    } catch (PDOException $e) {
        echo $e;
    }
}

$sql = "SELECT * FROM comandas ORDER BY id DESC";
$result = $conn->query($sql); ?>

<body>
    <div class="container-comandas">
        <div class="card-comanda add-comanda top-button" data-bs-toggle="modal" data-bs-target="#modalComanda"> + </div>
        <?php while ($row = $result->fetch()): ?> <a href="index.php?id=<?= $row['id'] ?>" class="card-comanda">
                <strong>#<?= $row['id'] ?></strong>
                <div class="nome-comanda"><?= $row['nome'] ?></div>
            </a> <?php endwhile; ?>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>