<?php

require_once("conexao.php");

try {
    if (isset($_GET["search"]) && !empty($_GET["search"])) {
        $search = $_GET["search"];
        $smtp = $conn->prepare("
            SELECT id, nome, descricao, preco, tipo, categoria
            FROM produto
            WHERE LOWER(nome) LIKE LOWER('%" . $search . "%')
            OR LOWER(tipo) = LOWER('" . $search . "')
            OR LOWER(categoria) = LOWER('" . $search . "')
            OR preco LIKE '%" . $search . "%'
        ");
        $smtp->execute();
        $results = $smtp->fetchAll(PDO::FETCH_ASSOC);    
    } else {
        $smtp = $conn->prepare("SELECT id, nome, descricao, preco, tipo, categoria FROM produto");
        $smtp->execute();
        $results = $smtp->fetchAll(PDO::FETCH_ASSOC);    
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

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
            <h3>Produtos</h3>

            <?php
                if (isset($search)) {
                    ?>
                        <span>Buscando por <b>"<?= $search ?>"</b>. <a href="/loja/index.php">Limpar</a></span>
                    <?php
                }
            ?>

            <?php
                if (empty($results)) {
                    ?>
                        <div class="alert alert-dark mt-3" role="alert">
                            Nenhum resultado encontrado.
                        </div>
                    <?php
                } else {
                    ?>                    
                        <table class="table table-hover table-dark border mt-3">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Categoria</th>
                                    <th scope="col">Preço</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($results as $item) {
                                        ?>
                                            <tr onclick="mostrarDetalhe(<?= $item["id"]?>)" role="button">
                                                <td scope="row"><?= $item["id"] ?></td>
                                                <td scope="row"><?= $item["nome"] ?></td>
                                                <td scope="row"><?= $item["tipo"] ?></td>
                                                <td scope="row"><?= $item["categoria"] ?></td>
                                                <td scope="row"><?= $item["preco"] ?></td>
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

        <script src="js/mostrar-detalhe.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    </body>
</html>
