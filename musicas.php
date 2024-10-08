<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Músicas</title>
    <link rel="stylesheet" href="form.css">
    <style>
        #interface {
    width: 500px;
    margin: 0 auto;
    background-color: #F0F0F0;
    padding: 20px;
    border-radius: 5px;
    font-family: sans-serif;
  }
  
  fieldset {
    border: 1px solid #CCCCCC;
    padding: 10px;
    margin-bottom: 10px;
  }
  
  legend {
    font-weight: bold;
    margin-bottom: 5px;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
  }
  
  th, td {
    border: 1px solid #CCCCCC;
    padding: 5px;
    text-align: left;
  }

  ul {
    list-style-type: none;
    padding: 0;
  }

  li {
    margin-bottom: 5px;
  }
  
  a {
    color: #007BFF;
    text-decoration: none;
  }
  
  a:hover {
    text-decoration: underline;
  }
    </style>
<body>
    <div id="interface">
        <fieldset>
            <legend>Lista de Músicas Registradas</legend>
            <ul>
                <?php
                try {
                    $connection = new PDO("mysql:host=localhost;dbname=POP_BD", "root", "");
                    $connection->exec("set names utf8");
                } catch (PDOException $e) {
                    echo "Falha: " . $e->getMessage();
                    exit();
                }

                $rs = $connection->prepare("SELECT nome FROM musicas"); // Seleciona apenas o nome da música

                if ($rs->execute()) {
                    while ($registro = $rs->fetch(PDO::FETCH_OBJ)) {
                        echo "<li>" . $registro->nome . "</li>"; 
                    }
                } else {
                    echo "Falha na seleção de músicas<BR>";
                }
                ?>
            </ul>
        </fieldset>
        <p><a href="Menu.html">Menu Principal</a></p>
        <a href="registro_musicas.php">Cadastrar Nova Música</a> 
    </div>
</body>
</html>