<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Bandas</title>
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
            <legend>Lista de Bandas Cadastradas</legend>
            <ul>
                <?php
                try {
                    $connection = new PDO("mysql:host=localhost;dbname=POP_BD", "root", "");
                    $connection->exec("set names utf8");
                } catch (PDOException $e) {
                    echo "Falha: " . $e->getMessage();
                    exit();
                }

                $rs = $connection->prepare("SELECT nome FROM bandas"); // Seleciona apenas o nome da banda

                if ($rs->execute()) {
                    while ($registro = $rs->fetch(PDO::FETCH_OBJ)) {
                        echo "<li>" . $registro->nome . "</li>"; 
                    }
                } else {
                    echo "Falha na seleção de bandas<BR>";
                }
                ?>
            </ul>
        </fieldset>
        <p><a href="aula_menu.php">Menu Principal</a></p>
        <a href="aulaCadastro.php">Cadastrar Nova Banda</a> 
    </div>
</body>
</html>