<?php
$erro = null;
$excluir = false;;

// Conexão com o banco de dados (substitua pelas suas credenciais)
try {
    $connection = new PDO("mysql:host=localhost;dbname=POP_BD", "root", "");
    $connection->exec("set names utf8"); 
} catch(PDOException $e) {
    echo "Falha: " . $e->getMessage();
    exit();
}

// Verificar se o formulário de exclusão foi enviado
if (isset($_REQUEST["excluir"]) && $_REQUEST["excluir"] == true) {
    if (isset($_POST["integrantes_id"]) && !empty($_POST["integrantes_id"])) {

        $sql = "DELETE FROM integrantes WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(1, $_POST["integrantes_id"]);
        $stmt->execute();

        if ($stmt->errorCode() != "00000") {
            $erro = "Erro código " . $stmt->errorCode() . ": ";
            $erro .= implode(", ", $stmt->errorInfo());
        } else {
            $excluir = true;
        }
    } else {
        $erro = "Selecione um integrante para excluir.";
    }
} 
// Obter todas as bandas para o dropdown
$sqlBandas = "SELECT id, nome FROM bandas";
$stmtBandas = $connection->query($sqlBandas);
$bandas = $stmtBandas->fetchAll(PDO::FETCH_ASSOC);

// Obter todos os integrantes para o select
$sqlIntegrantes = "SELECT id, nome, banda_id FROM integrantes ORDER BY nome";
$integrantes = $connection->query($sqlIntegrantes)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset=" UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exclusão de Membros</title>
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
    <h2>Exclusão de Integrantes da Banda</h2>

    <?php if ($excluir == true){ ?>
        <p>Membro excluído com sucesso!</p>
        <p><a href='integrantes.php'>Visualizar membros restantes</a></p>
    <?php }else{ ?>
        <?php if (isset($erro)){ ?>
            <p class='error'><?php echo $erro; ?></p>
        <?php } ?>

        <div class="form-container">
            <form method="POST" action="?excluir=true">
                <div class="form-group">
                    <label for="bandas_id" id="lblNome">Selecione a Banda:</label>
                    <select name="bandas_id" id="bandas_id">
                        <option value="">Escolha uma banda</option>
                        <?php foreach ($bandas as $banda){ ?>
                            <option value="<?php echo $banda['id']; ?>"><?php echo $banda['nome']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="integrantes_id" id="lblNome">Selecione o Integrante:</label>
                    <select name="integrantes_id" id="integrantes_id">
                        <option value="">Escolha um integrante</option>
                        <?php foreach ($integrantes as $integrante){ ?>
                            <option value="<?php echo $integrante['id']; ?>" data-banda-id="<?php echo $integrante['banda_id']; ?>">
                                <?php echo $integrante['nome']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <input type="submit" value="Excluir"><br>
                <a href="Menu.html">Menu</a>
            </form>
        </div>
    <?php }; ?>

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
    <script>
    // Script para filtrar integrantes por banda
    const selectBanda = document.getElementById('bandas_id');
    const selectIntegrante = document.getElementById('integrantes_id');

    selectBanda.addEventListener('change', function() {
        const bandaId = this.value;

        // Limpar opções do select de integrantes
        selectIntegrante.innerHTML = '<option value="">Escolha um integrante</option>';

        // Adicionar opções de integrantes da banda selecionada
        <?php foreach ($integrantes as $integrante){ ?>
            if (<?php echo $integrante['banda_id']; ?> == bandaId || bandaId === "") {
                const option = document.createElement('option');
                option.value = <?php echo $integrante['id']; ?>;
                option.textContent = '<?php echo $integrante['nome']; ?>';
                selectIntegrante.appendChild(option);
            }
        <?php }; ?>
    });
    </script>
</body>
</html>