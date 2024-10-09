
<?php 
$erro = null;
$atualizar = false;

// Conexão com banco de dados
try {
    $connection = new PDO("mysql:host=localhost;dbname=POP_BD", "root", "");
    $connection->exec("set names utf8");
} catch (PDOException $e) {
    echo "Falha na conexão com o banco de dados: " . $e->getMessage();
    exit();
}

// Atualização da música
if (isset($_REQUEST["atualizar"]) && $_REQUEST["atualizar"] == true) {
    if (isset($_POST["musicas_id"]) && !empty($_POST["musicas_id"])) {
        $novoNome = $_POST["novo_nome"];
        
        $sql = "UPDATE musicas SET nome = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(1, $novoNome);
        $stmt->bindParam(2, $_POST["musicas_id"]);
        $stmt->execute();

        if ($stmt->errorCode() != "00000") {
            $erro = "Erro código " . $stmt->errorCode() . ": ";
            $erro .= implode(", ", $stmt->errorInfo());
        } else {
            $atualizar = true;
        }
    } else {
        $erro = "Selecione uma música para atualizar.";
    }
} 

// Obter todas as músicas para o dropdown
$sqlMusicas = "SELECT id, nome FROM musicas";
$stmtMusicas = $connection->query($sqlMusicas);
$musicas = $stmtMusicas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualização de Músicas</title>
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
    <h2>Atualização de Músicas</h2>

    <?php if ($atualizar == true){ ?>
        <p>Música atualizada com sucesso!</p>
        <p><a href='musicas.php'>Visualizar músicas atualizadas</a></p>
    <?php } else { ?>
        <?php if (isset($erro)){?>
            <p class='error'><?php echo $erro; ?></p>
        <?php }; ?>

        <div class="form-container">
            <form method="POST" action="?atualizar=true">
                <div class="form-group">
                    <label for="musicas_id" id="lblNome">Selecione a Música:</label>
                    <select name="musicas_id" id="musicas_id" required>
                        <option value="">Escolha uma música</option>
                        <?php foreach ($musicas as $musica){ ?>
                            <option value="<?php echo $musica['id']; ?>"><?php echo $musica['nome']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="novo_nome">Novo Nome da Música:</label>
                    <input type="text" name="novo_nome" id="novo_nome" required>
                </div>
                <input type="submit" value="Atualizar"><br>
                <a href="Menu.html">Menu</a>
            </form>
        </div>
    <?php } ?>

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
</body>
</html>
