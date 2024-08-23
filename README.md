# Site Loja

Esse projeto é um site de loja onde é possível:
- Listar todos os produtos;
- Buscar os produtos por:
  - Nome;
  - Tipo;
  - Categoria;
  - Preço;
- Ver detalhes do produto clicando na linha da tabela;

## Configuração do Ambiente de Desenvolvimento

### Passo 1: Baixar e Instalar o XAMPP

1. Acesse o site oficial do XAMPP:
   - Visite https://www.apachefriends.org/

2. Escolha a versão do XAMPP:
   - Selecione a versão apropriada para o seu sistema operacional (Windows, Linux, ou macOS).

3. Faça o download:
   - Clique no botão de download e aguarde o término do download.

4. Instale o XAMPP:
   - Execute o instalador baixado.
   - Durante a instalação, selecione os componentes que deseja instalar. Por padrão, é recomendado instalar o Apache, MySQL, PHP, e phpMyAdmin.
   - Escolha o diretório de instalação (por padrão, é C:\xampp no Windows).

5. Complete a instalação:
   - Siga as instruções do instalador até a conclusão.

### Passo 2: Iniciar o XAMPP e Testar a Instalação

1. Abra o XAMPP Control Panel:
   - No Windows, você pode encontrar o XAMPP Control Panel no menu Iniciar.
   - No macOS ou Linux, abra o XAMPP através do diretório de instalação.

2. Inicie os serviços:
   - No painel de controle do XAMPP, clique em “Start” para o Apache e o MySQL.
   - Os serviços devem começar a rodar e mostrar o status como "Running".
Teste a instalação:

3. Abra um navegador e digite http://localhost na barra de endereços.
   - Se tudo estiver configurado corretamente, você verá a página inicial do XAMPP.

### Passo 3: Criando o banco de dados

1. Acessar o PHPMyAdmin:
   - Acesse **http://localhost/phpmyadmin**.
   - Na barra lateral do site, clique em "Novo".
   - Digite "loja" no campo "Nome do banco de dados", e "utf8_general_ci" na seleção ao lado, então clique em "Criar".
   - Clique em "Importar" na barra superior e selecione o arquivo **sql/loja.sql", então clique em "Importar" no final da página.


### Passo 4: Abrindo o projeto

1. Copiar arquivos:
   - Abra a pasta **C:\xampp\htdocs** no seu explorador de arquivos e crie uma pasta chamada **loja** com os arquivos do projeto, ou mova a pasta **loja** com os arquivos para **C:\xampp\htdocs**.
2. Abrir o site:
   - Digite **http://localhost/loja** para acessar o site.


## Consultas SQL utilizadas

#### Buscar todos os produtos
```sql
SELECT id, nome, descricao, preco, tipo, categoria
FROM produto
```

#### Buscar produtos de acordo com termo pesquisado
A busca funciona tanto por nome, quanto para tipo, categoria e preço.

```sql
SELECT id, nome, descricao, preco, tipo, categoria
FROM produto
WHERE LOWER(nome) LIKE LOWER('%TV%')
OR LOWER(tipo) = LOWER('TV')
OR LOWER(categoria) = LOWER('TV')
OR preco LIKE '%TV%'
```

#### Buscar características de um produto
```sql
SELECT c.nome, c.descricao
FROM produto_caracteristica AS pc
INNER JOIN caracteristica AS c
ON pc.id_caracteristica = c.id
WHERE pc.id_produto = 1
```
