function alterarQuantidadeCarrinho(id_produto, event) {
    const quantidade = parseInt(event.value);

    fetch(`/loja/carrinho.php?id=${id_produto}&quantidade=${quantidade}`).then((response) => {
        if (response.status > 400) {
            alert("Deu tudo errado")
        } else {
            const preco = document.getElementById(`preco-produto-${id_produto}`);
            const total = document.getElementById(`total-produto-${id_produto}`);
            total.innerHTML = (parseFloat(preco.innerHTML) * quantidade).toFixed(2);
            somarTotalCarrinho();
        }
    })
}

function somarTotalCarrinho() {
    const total_carrinho = document.getElementById("total-carrinho");
    const totais_produtos = document.querySelectorAll(".total-produto");

    let valor_total = 0;
    totais_produtos.forEach((total_produto) => {
        valor_total += parseFloat(total_produto.innerHTML);
    });

    total_carrinho.innerHTML = valor_total.toFixed(2);
}

somarTotalCarrinho();