<?php

// Inclui o arquivo de conexão com o banco de dados
require_once("conexao.php");

// Verifica se o parâmetro 'id' foi passado na URL e não está vazio
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    
    $id = $_GET["id"];
    // Verifica se o parâmetro 'quantidade' foi passado na URL; se não, define como 1
    $quantidade = (isset($_GET["quantidade"]) && !empty($_GET["quantidade"])) ? $_GET["quantidade"] : 1;

    //  INSERT / UPDATE -- Preparação da consulta SQL para inserir produto no carinho

    $stmt = $conn->prepare("
        INSERT INTO carrinho
            (id_produto, quantidade)
        VALUES
            (" . $id . ", " . $quantidade . ")
        ON DUPLICATE KEY
        UPDATE
            quantidade = VALUES(quantidade)
    ");
    $stmt->execute();
    // Redireciona o usuário de volta à página do carrinho
    header("Location: /loja/carrinho.php");
}

// SELECT - Preparação da consulta SQL para selecionar os produtos no carinho.
$stmt = $conn->prepare("
    SELECT p.id, p.nome, p.preco, c.quantidade,
           calcula_preco_final(p.preco, :desconto) AS preco_final
    FROM carrinho c
    INNER JOIN produto p ON c.id_produto = p.id
");
$stmt->bindValue(':desconto', 0.20); // Exemplo de desconto de 10%
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html lang="pt-br">
    <head>
        <title>Carrinho | Loja</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"/>
    </head>

    <body class="bg-dark text-white">
        
        <?php require("header.php") ?>

        <div class="container mt-3">
            <h3>Carrinho</h3>

            <?php
                if (count($results) == 0) {
                    ?>
                        <!-- Exibe mensagem caso o carrinho esteja vazio -->
                        <div class="alert alert-dark mt-4" role="alert">
                            Nenhum item no carrinho.
                        </div>
                    <?php
                } else {
                    ?>
                        <div class="d-flex justify-content-between align-items-end">
                            <span>
                                <?= count($results) ?> itens
                            </span>
                            <div class="d-flex flex-column align-items-end">
                                <small>Total</small>
                                <h3 id="total-carrinho">0</h3>
                                <form class="mt-2" action="finalizar-compra.php">
                                    <input type="submit" value="Finalizar compra" class="btn btn-success">
                                </form>
                            </div>
                        </div>

                        <hr/>

                        <table class="table table-hover table-dark mt-4">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Quantidade</th>
                                    <th scope="col">Preço unitário</th>
                                    <th scope="col">Total</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($results as $item) {
                                        ?>
                                            <tr>
                                                <!-- Exibe o ID e nome do produto -->
                                                <td scope="row"><?= $item["id"] ?></td>
                                                <td scope="row"><?= $item["nome"] ?></td>
                                                <!-- Seleciona a quantidade do produto no carrinho -->
                                                <td scope="row">
                                                    <select class="form-select" style="width: 100px" onchange="alterarQuantidadeCarrinho(<?= $item['id'] ?>, this)">
                                                        <?php
                                                            for ($i = 1; $i <= 20; $i++) {
                                                                ?>
                                                                     <!-- Marca a opção correspondente à quantidade atual -->
                                                                    <option <?= $item["quantidade"] == $i ? "selected" : "" ?>><?= $i ?></option>
                                                                <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td scope="row" id="preco-produto-<?= $item["id"] ?>"><?= $item["preco"] ?></td>
                                                <td scope="row" class="total-produto" id="total-produto-<?= $item["id"] ?>"><?= $item["preco_final"] * $item["quantidade"] ?></td>
                                                <td scope="row">
                                                    <a href="/loja/remove-carrinho.php?id=<?= $item["id"] ?>">
                                                        <i class="bi bi-trash2"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    <?php
                }
            ?>
        </div>
    </body>

    <script src="./js/alterar-quantidade-carrinho.js"></script>
</html>