<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comandas</title>

    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="CSS/geral.css">
</head>

<?php
if (isset($_SESSION['UserId']) and $_SESSION['UserId'] != 0) {
    $cookieNome = "UserId";
    $cookieValue = $_SESSION['UserId'];
    setcookie(name: "UserId", value: 0, expires_or_options: time() + (86400 * 30));

} else {
    echo "<script language=javascript>
            location.href='login.php'
        </script>";
}
?>

<?php
require_once("DataBase/conexao.php");
require("Functions.php");

if (isset($_SESSION['UserId'])) {
    $UserType = RequestUser(Id: $_SESSION['UserId'], What: 'usertype', conn: $conn);

    include "Header.php";

    switch ($UserType) {
        case "Admin";
            if (!isset($_GET['id'])) {
                include "Components/Administrador/painel.php";
            } else {
                include "Components/Administrador/comanda.php";
            }
            break;
        case "Atendente";
            if (!isset($_GET['id'])) {
                include "Components/Atendente/painel.php";
            } else {
                include "Components/Atendente/comanda.php";
            }
            break;
        case "Totem";
            echo "I am an totem";
            break;
    }

}
?>