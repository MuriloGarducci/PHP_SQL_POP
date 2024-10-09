<?php
session_start();

$erros = array();

// Dados de conexão com o banco de dados
$servername = "localhost";  // Endereço do servidor MySQL
$username = "root"; // Seu nome de usuário do MySQL
$password = ""; // Sua senha do MySQL
$dbname = "POP_BD";

// Conecta ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  $erros[] = "Falha na conexão: " . $conn->connect_error;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $senha = $_POST['password'];

  // Previne SQL Injection
  $stmt = $conn->prepare("SELECT * FROM Logins WHERE usuario = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verifica a senha com password_hash
    if (!password_verify($senha, $user['senha'])) {
      $erros[] = "Senha incorreta.";
    }
  } else {
    $erros[] = "Usuário não encontrado.";
  }

  if (empty($erros)) {
    $_SESSION['loggedin'] = true;
    $_SESSION['usuario'] = $email; // Armazena o email na sessão
    header("Location: Menu.html"); 
    exit;
  }
  $stmt->close();
}
$conn->close();
?>
<?php if (!empty($erros)) : ?>
  <div class="erros">
    <ul>
      <?php foreach ($erros as $erro) : ?>
        <li><?= $erro ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Login</title>
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: black;
    }

    .container {
      display: flex;
      margin-right: 30px;
    }

    .login-form {
      width: 300px;
      padding: 30px;
      text-align: center;
      color: white;
      border-radius: 5px;
    }

    .login-form input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: none;
      background-color: #333;
      color: white;
    }

    .login-form button {
      width: 100%;
      padding: 10px;
      background-image: linear-gradient(to right, rgb(138, 43, 226), rgb(138, 50, 250), rgb(199, 21, 133));
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
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
  color: white;
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
    <form class="login-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <h1>Faça seu login.</h1>
      <input type="email" name="email" placeholder="Email">
      <input type="password" id="senha" name="password" placeholder="Senha">
      <input type="checkbox" onclick="togglePasswordVisibility()">Mostrar/Ocultar Senha</input><br><br>
      <input type="submit" value="Entrar">
      <a href="Registro.php">Ainda não tenho uma conta</a>
    </form>

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
  </div>

  <script>
    function togglePasswordVisibility() {
      var input = document.getElementById("senha");
      if (input.type === "password") {
        input.type = "text";
      } else {
        input.type = "password";
      }
    }
  


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