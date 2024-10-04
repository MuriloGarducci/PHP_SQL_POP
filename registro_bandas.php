<?php
$erro = null;
$valido = false;

if (isset($_REQUEST["validar"]) && $_REQUEST["validar"] == true) {
    if (strlen(utf8_decode($_POST["nome"])) < 2) {
        $erro = "Preencha o campo nome da banda corretamente (2 ou mais caracteres)";
    } else {
        // Incluir código de conexão de banco de dados
        $valido = true;

        try {
            $connection = new PDO("mysql:host=localhost;dbname=POP_BD", "root", "");
            $connection->exec("set names utf8");
        } catch (PDOException $e) {
            echo "Falha: " . $e->getMessage();
            exit();
        }

        $sql = "INSERT INTO bandas (nome) VALUES (?)";

        $stmt = $connection->prepare($sql);

        $stmt->bindParam(1, $_POST["nome"]);

        $stmt->execute();

        if ($stmt->errorCode() != "00000") {
            $valido = false;
            $erro = "Erro código " . $stmt->errorCode() . ": ";
            $erro .= implode(", ", $stmt->errorInfo());
        }
        //Fim do código de conexão
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Bandas</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f2f2f2;
        }

        h2 {
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>Cadastro de Bandas</h2>
    <?php
    if ($valido == true) {
        echo "<p>Banda cadastrada com sucesso!</p>";
        echo "<p><a href='.php'>Visualizar bandas cadastradas</a></p>";
    } else {
        if (isset($erro)) {
            echo "<p class='error'>" . $erro . "</p>";
        }
        ?>
        <form method="POST" action="?validar=true">
            <label for="nome">Nome da Banda:</label>
            <input type="text" name="nome" id="nome" 
                   value="<?php if (isset($_POST["nome"])) {
                       echo $_POST["nome"];
                   } ?>">

            <input type="submit" value="Cadastrar">
        </form>
    <?php
    }
    ?>
</body>
</html>