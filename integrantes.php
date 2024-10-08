<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Músicas</title>
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
        li {
          list-style: none;
          margin-left: -20%;
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
    <div class="form-container">
        <fieldset>
            <legend>Integrantes das Bandas</legend>
            <?php
            try {
                $connection = new PDO("mysql:host=localhost;dbname=POP_BD", "root", "");
                $connection->exec("set names utf8");
            } catch (PDOException $e) {
                echo "Falha: " . $e->getMessage();
                exit();
            }

            // Consulta para selecionar bandas e seus integrantes
            $sql = "SELECT b.nome AS nome_banda, i.nome AS nome_integrante
                    FROM bandas AS b
                    LEFT JOIN integrantes AS i ON b.id = i.banda_id"; // Assumindo que você tem uma coluna 'banda_id' na tabela 'integrantes'

            $rs = $connection->query($sql);

            if ($rs) {
                $bandaAtual = "";
                while ($registro = $rs->fetch(PDO::FETCH_ASSOC)) {
                    $nomeBanda = $registro['nome_banda'];
                    $nomeIntegrante = $registro['nome_integrante'];

                    // Verifica se está em uma nova banda
                    if ($nomeBanda != $bandaAtual) {
                        // Se não for a primeira banda, fecha a lista anterior
                        if ($bandaAtual != "") {
                            echo "</ul>";
                        }

                        echo "<h2>$nomeBanda</h2>";
                        echo "<ul>";
                        $bandaAtual = $nomeBanda;
                    }

                    // Exibe o integrante
                    if ($nomeIntegrante) { // Verifica se a banda tem integrantes cadastrados
                        echo "<li>$nomeIntegrante</li>";
                    } else {
                        echo "<li>Nenhum integrante cadastrado.</li>"; 
                    }
                }

                // Fecha a última lista, se houver
                if ($bandaAtual != "") {
                    echo "</ul>";
                }
            } else {
                echo "Falha na consulta: " . $connection->errorInfo()[2];
            }
            ?>
        </fieldset>
        <p><a href="Menu.html">Menu Principal</a></p>
        <p><a href="registro_membro.php">Registrar Outro Integrante</a></p>
    
    </div>
    <script>
        const numStars = 200; // Número de estrelas
      const starsContainer = document.querySelector('.stars');

      for (let i = 0; i < numStars; i++) {
        const star = document.createElement('div');
        star.classList.add('star');

        // Posicione aleatoriamente dentro da janela de visualização
        const x = Math.random() * window.innerWidth;
        const y = Math.random() * window.innerHeight;
        star.style.left = `${x}px `;
        star.style.top = `${y}px`;

        starsContainer.appendChild(star);
      }
    </script>
</body>
</html>
