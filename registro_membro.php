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

        // Buscar membros da banda selecionada
        $sql = "SELECT id, nome FROM integrantes WHERE banda_id = ? ORDER BY nome";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(1, $_POST["banda_id"]);
        $stmt->execute();
        $membros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Inserir novo membro
        if (isset($_POST["nome"]) && !empty($_POST["nome"])) {
            $sql = "INSERT INTO integrantes (nome, banda_id) VALUES (?, ?)";
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
                header("Location: integrantes.php?banda_id=" . $_POST["banda_id"]);
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset=" UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Membros</title>
    <style>
body {
    margin-top:-7%;
    background-color: #000;
    color: #fff;
    font-family: sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    text-align: center;
}
h2 {
            color: white;
            font-size: 40px;

        }
.container {
    text-align: center;
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
.lbl{
    font-size: 30px;
}
a{
    text-decoration: none;
    color:white;
    font-size: 25px;
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
    <?php
    if ($valido == true) {
        echo "<p>Membro cadastrado com sucesso!</p>";
        echo "<p><a href='membros.php'>Visualizar bandas cadastradas</a></p>";
    } else {
        if (isset($erro)) {
            echo "<p class='error'>" . $erro . "</p>";
        }
        ?>
        <form method="POST" action="?validar=true">
        <h2>Cadastro de Membros</h2>
            <label for="banda_id" class="lbl">Banda:</label>
            <select name="banda_id" id="banda_id">
                <option value="">Selecione a banda</option>
                <?php
                foreach ($bandas as $banda) {
                    $selected = (isset($_GET["banda_id"]) && $_GET["banda_id"] == $banda["id"]) ? "selected" : "";
                    echo "<option value='" . $banda["id"] . "' " . $selected . ">" . $banda["nome"] . "</option>";
                }
                ?>
            </select>

            <label for="nome" class="lbl">Nome do Membro:</label>
            <input type="text" name="nome" id="nome" 
                   value="<?php if (isset($_POST["nome"])) {
                       echo $_POST["nome"];
                   } ?>">

            <input type="submit" value="Cadastrar">
            <a href="Menu.html">Menu</a>
        </form>

        <?php if (isset($_GET["banda_id"]) && !empty($_GET["banda_id"])) { ?>
            <h3>Membros da Banda:</h3>
            <ul>
                <?php
                $sql = "SELECT id, nome FROM integrantes WHERE banda_id = ? ORDER BY nome";
                $stmt = $connection->prepare($sql);
                $stmt->bindParam(1, $_GET["banda_id"]);
                $stmt->execute();
                $membros = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($membros as $membro) { ?>
                    <li><?php echo $membro["nome"]; ?></li>
                <?php } ?>
            </ul>
        <?php } ?>
    <?php
    }
    ?>
</body>
</html>
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
