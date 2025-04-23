<?php session_start(); include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .form-container {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
      background: #f9f9f9;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    .form-group input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    .btn {
      background: #4CAF50;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .btn:hover {
      background: #45a049;
    }
    .error {
      color: red;
      margin-top: 5px;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Login</h2>
    <form method="POST" id="loginForm">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
      </div>
      <button type="submit" name="login" class="btn">Login</button>
    </form>

<p class="text-center">Don't have an account? <a href="register.php">Register here</a></p>
    <script>
      document.getElementById('loginForm').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        if (!email || !password) {
          e.preventDefault();
          alert('Please fill in all fields');
        }
      });
    </script>

<?php
if (isset($_POST['login'])) {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if (empty($email) || empty($password)) {
    echo '<div class="error">Please fill in all fields</div>';
  } else {
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $stmt->bind_result($id, $hashed);
      $stmt->fetch();

      if (password_verify($password, $hashed)) {
        $_SESSION['user_id'] = $id;
        header("Location: index.php");
        exit();
      } else {
        echo '<div class="error">Invalid email or password</div>';
      }
    } else {
      echo '<div class="error">Invalid email or password</div>';
    }
  }
}
?>
</body>
</html>
