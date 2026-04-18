<header class="d-flex align-items-center justify-content-between px-3 py-2" style="background:#1C1C1C; color:white;">

    <div class="d-flex align-items-center gap-2">
        <img src="Imagens/logo.png" alt="Logo" style="height:75px;">
    </div>

    <h1 class="titulo">
        <?php
        $exibirTipo = isset($UserType) ? $UserType : (isset($_SESSION['usertype']) ? $_SESSION['usertype'] : '');

        if ($exibirTipo == 'Admin') {
            echo "ADMINISTRADOR";
        } else {
            echo "PAINEL DE COMANDAS";
        }
        ?>
    </h1>

    <form method="POST" action="index.php">
        <button name="sair" id="sair" class="btn btn-danger d-flex align-items-center gap-2">

            <i class="fa-solid fa-right-from-bracket"></i>

            <span class="d-none d-md-inline">Sair</span>

        </button>
    </form>
</header>
<?php
if (isset($_REQUEST["sair"])) {
    $_SESSION["UserId"] = 0;
    echo "<script language=javascript>
              location.href = 'index.php';
              </script>";
}
?>