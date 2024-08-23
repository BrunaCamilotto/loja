<?php

require_once("conexao.php");

try {

    // Buscar filtros na URL

    $queryParams = explode("&", $_SERVER["QUERY_STRING"]);

    $search = "";
    $data_lancamento = "";
    $min_preco = "";
    $max_preco = "";
    $tipos = array();
    $categorias = array();

    if (count($queryParams) > 0) {
        if (isset($_GET["search"]) && !empty($_GET["search"])) {
            $search = $_GET["search"];
        }
        if (isset($_GET["data_lancamento"]) && !empty($_GET["data_lancamento"])) {
            $data_lancamento = $_GET["data_lancamento"];
        }
        if (isset($_GET["min_preco"]) && !empty($_GET["min_preco"])) {
            $min_preco = intval($_GET["min_preco"]);
        }
        if (isset($_GET["max_preco"]) && !empty($_GET["max_preco"])) {
            $max_preco = intval($_GET["max_preco"]);
        }
        if (isset($_GET["tipo"]) && !empty($_GET["tipo"])) {
            foreach ($queryParams as $param) {
                if (strpos($param, '=') === false) $param += '=';
                list($name, $value) = explode('=', $param, 2);
                if ($name == "tipo") {
                    $tipos[] = urldecode($value);
                }
            }
        }
        if (isset($_GET["categoria"]) && !empty($_GET["categoria"])) {
            foreach ($queryParams as $param) {
                if (strpos($param, '=') === false) $param += '=';
                list($name, $value) = explode('=', $param, 2);
                if ($name == "categoria") {
                    $categorias[] = urldecode($value);
                }
            }
        }
    }

    // Criar query com filtros

    $query = "SELECT id, nome, descricao, preco, tipo, categoria, data_lancamento FROM produto";
    $filtros = array();

    if (!empty($search)) {
        $filtros[] = "(LOWER(nome) LIKE LOWER('%" . $search . "%') OR LOWER(descricao) LIKE LOWER('%" . $search . "%'))";
    }
    if (!empty($data_lancamento)) {
        $filtros[] = "data_lancamento >= '" . $data_lancamento . "'";
    }
    if (count($tipos) > 0) {
        $filtros[] = "tipo IN ('" . implode("','", $tipos) . "')";
    }
    if (count($categorias) > 0) {
        $filtros[] = "categoria IN ('" . implode("','", $categorias) . "')";
    }
    if (!empty($min_preco)) {
        $filtros[] = "preco >= " . $min_preco;
    }
    if (!empty($max_preco)) {
        $filtros[] = "preco <= " . $max_preco;
    }

    // Juntar filtros

    if (count($filtros) > 0) {
        $query = $query . " WHERE " . implode(" AND ", $filtros);
    }

    // Exibindo Query com filtros ou pesquisa
    //echo "<pre>";
    //echo $query;
    //echo "</pre>";


    // Buscar no banco de dados

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"/>
    </head>

    <body>
        <main class="bg-dark text-light vh-100 d-flex flex-column">
            <?php require("header.php") ?>

            <div class="flex-fill d-flex flex-row overflow-hidden">
                <form class="border-end w-25 d-flex flex-column" action="/loja/index.php" method="GET">
                    <div class="flex-fill d-flex flex-column overflow-auto p-3">

                        <!-- Pesquisa (escondido) -->
    
                        <input type="hidden" name="search" value="<?= $search ?>">
    
                        <!-- Filtro Lançamento -->
    
                        <label class="form-label" for="data_lancamento">Data de lançamento (a partir de)</label>
                        <input class="form-control" name="data_lancamento" type="date" value="<?= $data_lancamento ?>" />
    
                        <!-- Filtro Preço -->
    
                        <label class="form-label mt-3" for="min_preco">Preço</label>
                        <div class="d-flex gap-2">
                            <input class="form-control" type="number" name="min_preco" placeholder="Mínimo" min="0" value="<?= $min_preco ?>" />
                            <span>-</span>
                            <input class="form-control" type="number" name="max_preco" placeholder="Máximo" min="0" value="<?= $max_preco ?>" />
                        </div>
    
                        <!-- Filtro Tipo -->
    
                        <label class="form-label mt-3" for="tipo">Tipo</label>
                        <div class="form-check">
                            <input class="form-check-input" name="tipo" type="checkbox" value="Novo" id="tipoNovo" <?= in_array("Novo", $tipos) ? "checked" : "" ?> />
                            <label class="form-check-label" for="tipoNovo">Novo</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="tipo" type="checkbox" value="Usado" id="tipoUsado" <?= in_array("Usado", $tipos) ? "checked" : "" ?> />
                            <label class="form-check-label" for="tipoUsado">Usado</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="tipo" type="checkbox" value="Promoção" id="tipoPromoção" <?= in_array("Promoção", $tipos) ? "checked" : "" ?> />
                            <label class="form-check-label" for="tipoPromoção">Promoção</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="tipo" type="checkbox" value="Liquidação" id="tipoLiquidação" <?= in_array("Liquidação", $tipos) ? "checked" : "" ?> />
                            <label class="form-check-label" for="tipoLiquidação">Liquidação</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="tipo" type="checkbox" value="Outros" id="tipoOutros" <?= in_array("Outros", $tipos) ? "checked" : "" ?> />
                            <label class="form-check-label" for="tipoOutros">Outros</label>
                        </div>
    
                        <!-- Filtro de categoria -->
    
                        <label class="form-label mt-3" for="categoria">Categoria</label>
                        <div class="form-check">
                            <input class="form-check-input" name="categoria" type="checkbox" value="Eletronico" id="categoriaEletronico" <?= in_array("Eletronico", $categorias) ? "checked" : "" ?> />
                            <label class="form-check-label" for="categoriaEletronico">Eletronico</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="categoria" type="checkbox" value="Telefonia" id="categoriaTelefonia" <?= in_array("Telefonia", $categorias) ? "checked" : "" ?> />
                            <label class="form-check-label" for="categoriaTelefonia">Telefonia</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="categoria" type="checkbox" value="Informatica" id="categoriaInformatica" <?= in_array("Informatica", $categorias) ? "checked" : "" ?> />
                            <label class="form-check-label" for="categoriaInformatica">Informatica</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="categoria" type="checkbox" value="Eletrodomesticos" id="categoriaEletrodomesticos" <?= in_array("Eletrodomesticos", $categorias) ? "checked" : "" ?> />
                            <label class="form-check-label" for="categoriaEletrodomesticos">Eletrodomesticos</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="categoria" type="checkbox" value="Acessorios" id="categoriaAcessorios" <?= in_array("Acessorios", $categorias) ? "checked" : "" ?> />
                            <label class="form-check-label" for="categoriaAcessorios">Acessorios</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="categoria" type="checkbox" value="Outros" id="categoriaOutros" <?= in_array("Outros", $categorias) ? "checked" : "" ?> />
                            <label class="form-check-label" for="categoriaOutros">Outros</label>
                        </div>
                    </div>
                    
                    <footer class="p-3 d-flex gap-2">
                        <a class="btn btn-outline-light flex-fill" href="/loja/index.php?search=<?= $search ?>">Limpar</a>
                        <input class="btn btn-light flex-fill" type="submit" value="Filtrar">
                    </footer>
                </form>

                <div class="container pt-3">
                    <h4>Produtos</h4>

                    <?php
                        if (!empty($search)) {
                            ?>
                                <span>Buscando por <b>"<?= $search ?>"</b>. <a href="/loja/index.php">Limpar</a></span>
                            <?php
                        }
                    ?>
        
                    <?php
                        if (empty($results)) {
                            ?>
                                <div class="alert alert-dark mt-4" role="alert">
                                    Nenhum resultado encontrado.
                                </div>
                            <?php
                        } else {
                            ?>                    
                                <table class="table table-hover table-dark mt-4">
                                    <thead>
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Tipo</th>
                                            <th scope="col">Categoria</th>
                                            <th scope="col">Data de Lançamento</th>
                                            <th scope="col">Preço</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($results as $item) {
                                                ?>
                                                    <tr onclick="mostrarDetalhe(<?= $item['id']?>)" role="button">
                                                        <td scope="row"><?= $item["id"] ?></td>
                                                        <td scope="row"><?= $item["nome"] ?></td>
                                                        <td scope="row"><?= $item["tipo"] ?></td>
                                                        <td scope="row"><?= $item["categoria"] ?></td>
                                                        <td scope="row"><?= $item["data_lancamento"] ?></td>
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
            </div>
        </main>


        <script src="js/mostrar-detalhe.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    </body>
</html>
