<?php
// Verifica se existe e se não está vazio
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    exit(404);
}

require_once("conexao.php");

$id = $_GET["id"];

try {
    // Prepara a consulta SQL
    $stmt = $conn->prepare("SELECT nome, tipo, categoria, descricao, preco, data_lancamento, desconto_usados FROM produto WHERE id = " . $id);
    // Executa
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("
        SELECT c.nome, c.descricao
        FROM produto_caracteristica AS pc
        INNER JOIN caracteristica AS c
        ON pc.id_caracteristica = c.id
        WHERE pc.id_produto = " . $id
    );
    $stmt->execute();
    $caracteristicas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

//echo "bunda" - foi só para testar rsrs

?>

<!doctype html>
<html lang="pt-br">
    <head>
        <title><?= $produto["nome"] ?> | Loja</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"/>
    </head>

    <body class="bg-dark text-white">
        
        <?php require("header.php") ?>

        <div class="container mt-3">
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
                <div class="col-12 col-md-6 d-flex flex-column align-items-end justify-content-end">
                    <?php
                        if ($produto["desconto_usados"] > 0) {
                            ?>
                                <span>Desconto: R$ <?= $produto["desconto_usados"] ?></span>
                            <?php
                        }
                    ?>
                    <h3>R$ <?= $produto["preco"] ?></h3>      

                    <form action="/loja/carrinho.php" method="GET">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input class="btn btn-success mt-1" type="submit" value="Adicionar ao carrinho">
                    </form>
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