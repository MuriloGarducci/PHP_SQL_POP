<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Integrantes das Bandas</title>
    <link rel="stylesheet" href="form.css">
    <style>
        /* Estilos CSS podem ser adicionados aqui, se necessário */
    </style>
</head>
<body>
    <div id="interface">
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

                        echo "<h3>$nomeBanda</h3>";
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
    </div>
</body>
</html>