<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
  <title>Registro</title>
  <style>
body {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: black;
  margin: 0;
  padding: 0;
  
}
.container{
  padding: ;
}
.register-form {
  width: 300px;
  padding: 30px;
  
  text-align: center;
  color: white;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
  
}

.register-form input {
  
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border: none;
  background-image: linear-gradient(to right, rgb(138, 43, 226), rgb(199, 21, 133));
  color: white;
  border-radius: 5px;
}

.register-form input:focus {
  outline: none;
  box-shadow: 0 0 5px rgba(138, 43, 226, 0.5);
}

.register-form button {
  width: 100%;
  padding: 10px;
  background-image: linear-gradient(to right, rgb(138, 43, 226), rgb(199, 21, 133));
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.register-form button:hover {
  background-image: linear-gradient(to right, rgb(199, 21, 133), rgb(138, 43, 226));
}

.register-form a {
  text-decoration: none;
  color: white;
}

.register-form a:hover {
  color: rgb(138, 43, 226);
}
h1 {
color: white;
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

        .erros {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  color:white;
text-align: center;
  border-bottom: 1px solid #ccc;
}

.erros ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.erros li {
  margin-bottom: 10px;
}
  </style>
</head>
<body>
<div class="stars"></div>

  <div class="container">
    <form class="register-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      
      <h1>Crie sua conta</h1>
      <input type="email" name="email" placeholder="Email">
      <input type="password" id="senha" name="senha" placeholder="Senha">
      <input type="password" id="confirmaSenha" placeholder="Confirme sua senha">
      <input type="checkbox" onclick="togglePasswordVisibility()">Mostrar/Ocultar Senha</input><br><br>
      <a href="login.php"><input type="submit" value="Enviar"></a>
      <p>Já possui uma conta? <a href="login.php">Faça login</a></p>
    
  </form>
     
  </div>

  <script>
    function togglePasswordVisibility() {
      var senha = document.getElementById("senha");
      var confirmaSenha = document.getElementById("confirmaSenha");
      if (senha.type === "password") {
        senha.type = "text";
        confirmaSenha.type = "text";
      } else {
        senha.type = "password";
        confirmaSenha.type = "password";
      }
    }
  </script>
</body>
</html>

<?php
$erros = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Dados de conexão com o banco de dados
  $servername = "localhost";  // Endereço do servidor MySQL
  $username = "root"; // Seu nome de usuário do MySQL
  $password = ""; // Sua senha do MySQL
  $dbname = "POP_BD";

  // Cria a conexão
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Verifica a conexão
  if ($conn->connect_error) {
    $erros[] = "Falha na conexão: " . $conn->connect_error;
  }

  // Recebe os dados do formulário
  $usuario = $_POST["email"];
  $senha = $_POST["senha"];

  // Verifica se ambos os campos estão preenchidos
  if (empty($usuario) || empty($senha)) {
    $erros[] = "Erro: ambos os campos devem ser preenchidos";
  }

  // Verifica se a senha tem menos de 8 caracteres
  if (strlen($senha) < 8) {
    $erros[] = "Erro: a senha deve ter pelo menos 8 caracteres";
  }

  // Verifica se o usuário é um endereço válido de email
  if (!filter_var($usuario, FILTER_VALIDATE_EMAIL)) {
    $erros[] = "Erro: endereço de email inválido";
  }

  // Verifica se já há um usuário com o mesmo email cadastrado
  $sql = "SELECT * FROM Logins WHERE usuario = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $usuario);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $erros[] = "Erro: usuário já cadastrado";
  }

  // Se não houver erros, executa a consulta SQL
  if (empty($erros)) {
    // Hash da senha para segurança
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Prepara a consulta SQL
    $sql = "INSERT INTO Logins (usuario, senha) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $senhaHash); 

    // Executa a consulta
    if (!$stmt->execute()) {
      $erros[] = "Erro ao registrar: " . $stmt->error;
    } else {
      $erros[] = "Registro realizado com sucesso!";
    }
  }

  // Fecha a conexão
  $stmt->close();
  $conn->close();
}
?>

<!-- Exibe as mensagens de erro -->
<?php if (!empty($erros)) : ?>
  <div class="erros">
    <ul>
      <?php foreach ($erros as $erro) : ?>
        <li><?= $erro ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>
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