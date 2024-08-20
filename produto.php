<?php

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    exit(404);
}

require_once("conexao.php");

$id = $_GET["id"];

try {
    $smtp = $conn->prepare("SELECT nome, tipo, categoria, descricao, preco, data_lancamento, desconto_usados FROM produto WHERE id = " . $id);
    $smtp->execute();
    $produto = $smtp->fetch(PDO::FETCH_ASSOC);

    $smtp = $conn->prepare("
        SELECT c.nome, c.descricao
        FROM produto_caracteristica AS pc
        INNER JOIN caracteristica AS c
        ON pc.id_caracteristica = c.id
        WHERE pc.id_produto = " . $id
    );
    $smtp->execute();
    $caracteristicas = $smtp->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

//echo "bunda" - foi só para testar rsrs

?>

<!doctype html>
<html lang="pt-br">
    <head>
        <title>Loja</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"/>
    </head>

    <body class="bg-dark text-white">
        
        <?php require("header.php") ?>

        <div class="container">
            <a href="/loja" class="link-primary">Voltar</a>

            <h2 class="mt-3"><?= $produto["nome"] ?></h2>
            
            <div class="row mb-5">
                <div class="col-12 col-md-6 d-flex flex-column">
                    <span><b>Tipo:</b> <?= $produto["tipo"] ?></span>
                    <span><b>Categoria:</b> <?= $produto["categoria"] ?></span>
                    <span><b>Lançamento:</b> <?= $produto["data_lancamento"] ?></span>
                    <br/>
                    <span><?= $produto["descricao"] ?></span>
                </div>
                <div class="col-12 col-md-6 d-flex flex-column align-items-end">
                    <?php
                        if ($produto["desconto_usados"] > 0) {
                            ?>
                                <span>Desconto: R$ <?= $produto["desconto_usados"] ?></span>
                            <?php
                        }
                    ?>
                    <h3>R$ <?= $produto["preco"] ?></h3>                    
                </div>
            </div>

            <h4>Características</h4>
            <table class="table table-dark border mt-3">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($caracteristicas as $item) {
                            ?>
                                <tr>
                                    <td scope="row"><?= $item["nome"] ?></td>
                                    <td scope="row"><?= $item["descricao"] ?></td>
                                </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    </body>
</html>