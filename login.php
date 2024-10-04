<?php
session_start();

// Dados de conexão com o banco de dados
$servername = "localhost";  // Endereço do servidor MySQL
$username = "root"; // Seu nome de usuário do MySQL
$password = ""; // Sua senha do MySQL
$dbname = "POP_BD";

// Conecta ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Falha na conexão: " . $conn->connect_error);
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
    if (password_verify($senha, $user['senha'])) {
      $_SESSION['loggedin'] = true;
      $_SESSION['usuario'] = $email; // Armazena o email na sessão
      header("Location: Menu.html"); 
      exit;
    } else {
      echo "Senha incorreta.";
    }
  } else {
    echo "Usuário não encontrado.";
  }
  $stmt->close();
}
$conn->close();
?>
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
  </style>
</head>

<body>
  <div class="container">
    <form class="login-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <h1>Faça seu login.</h1>
      <input type="email" name="email" placeholder="Email">
      <input type="password" id="senha" name="password" placeholder="Senha">
      <input type="checkbox" onclick="togglePasswordVisibility()">Mostrar/Ocultar Senha</input><br><br>
      <input type="submit" value="Entrar">
      <a href="Registro.php">Ainda não tenho uma conta</a>
    </form>

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
  </script>
</body>

</html>
