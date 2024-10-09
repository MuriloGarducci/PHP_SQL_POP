<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Músicas</title>
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

        .musicas {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .musica {
            margin: 10px;
            padding: 10px;
            border: 1px solid white;
            border-radius: 5px;
            background-color: #333;
            color: white;
            width: 150px;
            text-align: center;
        }
    </style>
 </head>
<body>
    <div class="stars"></div>
    <div class="form-container">
        <form action="" method="get">
            <label for="search">Pesquisar Música:</label>
            <input type="text" id="search" name="search" />
            <input type="submit" value="Pesquisar" />
        </form>
    </div>
        <?php try {
                $connection = new PDO("mysql:host=localhost;dbname=POP_BD", "root", "");
                $connection->exec("set names utf8");
            } catch (PDOException $e) {
                echo "Falha: " . $e->getMessage();
                exit();
            }

            if (isset($_GET['search']) && $_GET['search'] != '') {
                $search_term = $_GET['search'];
                $search_term = strtolower($search_term);
                $rs = $connection->prepare("SELECT nome FROM musicas WHERE LOWER(nome) LIKE :search");
                $rs->bindParam(':search', $search_term);
                $rs->execute();
                $musicas = $rs->fetchAll(PDO::FETCH_OBJ);
                if (count($musicas) == 0) {
                    echo '<p class="error">Não foi encontrada a música com o nome "' . $search_term . '"</p>';
                } else {
                    echo '<h2>Resultado da Pesquisa:</h2>';
                    echo '<div class="musicas">';
                    foreach ($musicas as $musica) {
                        echo '<div class="musica">' . $musica->nome . '</div>';
                    }
                    echo '</div>';
                }
            } else {
                $rs = $connection->prepare("SELECT nome FROM musicas");
                $rs->execute();
                $musicas = $rs->fetchAll(PDO::FETCH_OBJ);
                echo '<h2>Todas as Músicas:</h2>';
                echo '<div class="musicas">';
                foreach ($musicas as $i => $musica) {
                    echo '<div class="musica">' . $musica->nome . '</div>';
                    if (($i + 1) % 3 == 0) {
                        echo '<div style="clear:both;"></div>';
                    }
                }
                echo '</div>';
            }
        ?>
        <div class="form-container">
            <p><a href="Menu.html">Menu</a></p>
        </div>
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
            star.style.left = `${x}px`;
            star.style.top = `${y}px`;

            starsContainer.appendChild(star);
        }
    </script>
</body>
</html>