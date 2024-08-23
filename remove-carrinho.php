<?php

require_once("conexao.php");
// Verifica se existe e não está vazio
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    
    $id = $_GET["id"];
    $stmt = $conn->prepare("
        DELETE FROM carrinho
        WHERE id_produto = " . $id . "
    ");
    $stmt->execute();
}

header("Location: /loja/carrinho.php");
