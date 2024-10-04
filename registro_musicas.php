<?php
$erro = null;
$valido = false;

// Incluir código de conexão de banco de dados
try {
    $connection = new PDO("mysql:host=localhost;dbname=POP_BD", "root", "");
    $connection->exec("set names utf8");
} catch (PDOException $e) {
    echo "Falha: " . $e->getMessage();
    exit();
}

// Buscar bandas cadastradas para o select
$sql = "SELECT id, nome FROM bandas ORDER BY nome";
$bandas = $connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);

if (isset($_REQUEST["validar"]) && $_REQUEST["validar"] == true) {
    if (empty($_POST["banda_id"])) {
        $erro = "Selecione a banda.";
    } else {
        $valido = true;

        // Buscar músicas da banda selecionada
        $sql = "SELECT id, nome FROM musicas WHERE banda_id = ? ORDER BY nome";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(1, $_POST["banda_id"]);
        $stmt->execute();
        $musicas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Inserir nova música
        if (isset($_POST["nome"]) && !empty($_POST["nome"])) {
            $sql = "INSERT INTO musicas (nome, banda_id) VALUES (?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(1, $_POST["nome"]);
            $stmt->bindParam(2, $_POST["banda_id"]);
            $stmt->execute();

            if ($stmt->errorCode() != "00000") {
                $valido = false;
                $erro = "Erro código " . $stmt->errorCode() . ": ";
                $erro .= implode(", ", $stmt->errorInfo());
            } else {
                // Redirecionamento após inserção bem-sucedida
                header("Location: musicas.php?banda_id=" . $_POST["banda_id"]);
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Músicas</title>
    <style>
   body {
    background-color: #000;
    color: #fff;
    font-family: sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
.container {
    text-align: center;
}
input[type="text"],
input[type="submit"] {
    padding: 10px;
    margin: 5px;
    border: none;
    border-radius: 5px;
    background-color: #333;
    color: #fff;
}
input[type="submit"] {
    background-image: linear-gradient(to right, rgb(138, 43, 226), rgb(138, 50, 250), rgb(199, 21, 133));
    cursor: pointer;
}
label {
    font-size: 22px;
}
h2 {
    font-size: 30px;
}
    </style>
</head>
<body>

    <?php
    if ($valido == true) {
        echo "<p>Música cadastrada com sucesso!</p>";
        echo "<p><a href='musicas.php'>Visualizar bandas cadastradas</a></p>";
    } else {
        if (isset($erro)) {
            echo "<p class='error'>" . $erro . "</p>";
        }
        ?>
        <form method="POST" action="?validar=true">
            <h2>Cadastro de Músicas</h2>
            <label for="banda_id">Banda:</label>
            <br>
            <select name="banda_id" id="banda_id">
                <option value="">Selecione a banda</option>
                <?php
                foreach ($bandas as $banda) {
                    $selected = (isset($_GET["banda_id"]) && $_GET["banda_id"] == $banda["id"]) ? "selected" : "";
                    echo "<option value='" . $banda["id"] . "' " . $selected . ">" . $banda["nome"] . "</option>";
                }
                ?>
            </select>
            <br>
            <label for="nome">Nome da Música:</label>
            <br>
            <input type="text" name="nome" id="nome" 
                   value="<?php if (isset($_POST["nome"])) {
                       echo $_POST["nome"];
                   } ?>">
            <br>
            <input type="submit" value="Cadastrar">
        </form>

        <?php if (isset($_GET["banda_id"]) && !empty($_GET["banda_id"])) { ?>
            <h3>Músicas da Banda:</h3>
            <ul>
                <?php
                $sql = "SELECT id, nome FROM musicas WHERE banda_id = ? ORDER BY nome";
                $stmt = $connection->prepare($sql);
                $stmt->bindParam(1, $_GET["banda_id"]);
                $stmt->execute();
                $musicas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($musicas as $musica) { ?>
                    <li><?php echo $musica["nome"]; ?></li>
                <?php } ?>
            </ul>
        <?php } ?>
    <?php
    }
    ?>
</body>
</html>