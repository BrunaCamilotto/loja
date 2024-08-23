function alterarQuantidadeCarrinho(id_produto, event) {
      // Converte o valor para inteiro
    const quantidade = parseInt(event.value);

    // Faz uma requisição fetch, passando o id do produto e a quantidade
    fetch(`/loja/carrinho.php?id=${id_produto}&quantidade=${quantidade}`).then((response) => {
        // Verifica se o status da resposta indica um erro (status HTTP acima de 400(problema de conexão, um erro no servidor, ou uma falha ao processar a solicitação no backend)) 
        if (response.status > 400) {
            alert("Deu tudo errado")
        } else {
            // Caso a resposta seja bem-sucedida, atualiza a pagina
            const preco = document.getElementById(`preco-produto-${id_produto}`);
            const total = document.getElementById(`total-produto-${id_produto}`);
            total.innerHTML = (parseFloat(preco.innerHTML) * quantidade).toFixed(2);
            // Chama a função para recalcular o total do carrinho, somando os valores de todos os produtos
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
    // Atualiza o valor total do carrinho, formatando para 2 casas decimais
    total_carrinho.innerHTML = valor_total.toFixed(2);
}

somarTotalCarrinho();