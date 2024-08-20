<?php
if (isset($_GET["search"]) && !empty($_GET["search"])) {
    $search = $_GET["search"];
} else {
    $search = "";
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5">
    <div class="container-fluid">
        <a class="navbar-brand" href="/loja">
            Loja da
            <b>Bruna</b>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <form class="d-flex flex-fill justify-content-end gap-3" action="index.php" method="GET">
                <input
                    class="form-control w-auto flex-grow-1 flex-md-grow-0"
                    name="search"
                    type="search"
                    placeholder="Busca"
                    aria-label="Busca"
                    value="<?= $search ?>"
                >
                <button class="btn btn-outline-primary" type="submit">Busca</button>
            </form>
        </div>
    </div>
</nav>