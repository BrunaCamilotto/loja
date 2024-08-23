<?php


require_once("conexao.php");

// Temporário (enquanto não houver login)
$stmt = $conn->prepare("
    DELETE FROM carrinho
    WHERE 1
");
$stmt->execute();

?>

<!doctype html>
<html lang="pt-br">
    <head>
        <title>Finalizar Compra | Loja</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"/>
    </head>

    <body class="bg-dark text-white">
        <div class="wh-100 vh-100 d-flex flex-column">
            <?php require("header.php") ?>

            <div class="container flex-fill d-flex flex-column align-items-center justify-content-center">
                <h1>Agradecemos sua compra!</h1>
                <span>Você logo receberá um e-mail de confirmação do seu carrinho.</span>
                <a href="/loja/index.php" class="btn btn-success mt-4">Voltar à página inicial</a> 
            </div>
        </div>

    </body>
</html>
