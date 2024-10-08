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
            margin-top:10%;
            font-family: sans-serif;
            background-color: black;
            color: white;
            text-align: center;
        }

        h2 {
            color: white;
            font-size: 40px;

        }
        #lblNome {
            font-size:30px;   
            margin-top:-20px;    
        }
        .form-container {
            width: 300px;
            margin: 40px auto;
            padding: 20px;
            background-color: black;
            border-radius: 5px;
            
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: white;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid white;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: black;
            color: white;
        }

        input[type="text"],
input[type="submit"],
select { /* Adicione o select */
    padding: 10px;
    margin: 5px 0; /* Altere o margin para 5px em cima e embaixo */
    border: none;
    border-radius: 5px;
    background-color: #333;
    color: #fff;
    width: 100%; /* Ajuste a largura para ocupar o espaço disponível */
    box-sizing: border-box; /* Inclua o padding e border na largura */
}
input[type="submit"] {
    background-image: linear-gradient(to right, rgb(138, 43, 226), rgb(138, 50, 250), rgb(199, 21, 133));
    cursor: pointer;
}
label {
    display: block; /* Faz a label ocupar uma linha completa */
    margin-bottom: 5px; /* Espaço entre a label e o input */
}
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        a{
    text-decoration: none;
    color:white;
    font-size: 25px;
}

        .error {
            color: red;
            margin-bottom: 10px;
        }

        .stars {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1; /* Estrelas atras do body */
        }

        .star {
            position: absolute;
            width: 2px;
            height: 2px;
            background-color: #fff;
            border-radius: 50%;
            opacity: 0;
            animation: twinkle 2s infinite linear;
        }

        @keyframes twinkle {
            0% {
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }

        .star:nth-child(1) {
            width: 1px;
            height: 1px;
            animation-delay: 0.1s;
        }

        .star:nth-child(2) {
            width: 3px;
            height: 3px;
            animation-delay: 0.5s;
        }

        .star:nth-child(3) {
            width: 2px;
            height: 2px;
            animation-delay: 1s;
        }
    </style>
</head>
<body>
    <div class="stars"></div>
    <h2>Cadastro de Bandas</h2>
    <?php
    if ($valido == true) {
        echo "<p>Banda cadastrada com sucesso!</p>";
        echo "<p><a href='bandas.php'>Visualizar bandas cadastradas</a></p>";
    } else {
        if (isset($erro)) {
            echo "<p class='error'>" . $erro . "</p>";
        }
        ?>
        <div class="form-container">
            <form method="POST" action="?validar=true">
                <div class="form-group">
                    <label for="nome" id="lblNome">Nome da Banda:</label>
                    <input type="text" name="nome" id="nome" 
                           value="<?php if (isset($_POST["nome"])) {
                               echo $_POST["nome"];
                           } ?>">
                </div>
                <input type="submit" value="Cadastrar"><br>
                <a href="Menu.html">Menu</a>
            </form>
        </div>
    <?php
    }
    ?>
    <script>
       
    const numStars = 200; // Número de estrelas
  const starsContainer = document.querySelector('.stars');

  for (let i = 0; i < numStars; i++) {
    const star = document.createElement('div');
    star.classList.add('star');

    // Posicione aleatoriamente dentro da janela de visualização
    const x = Math.random() * window.innerWidth;
    const y = Math.random() * window.innerHeight;
    star.style.left = `${x}px`;
    star.style.top = `${y}px`;

    starsContainer.appendChild(star);
  }
</script>
    </script>
</body>
</html>