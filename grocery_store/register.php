<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
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
    .password-strength {
      margin-top: 5px;
      height: 5px;
      background: #ddd;
      border-radius: 2px;
    }
    .strength-weak { background: #ff5252; width: 33%; }
    .strength-medium { background: #ffb74d; width: 66%; }
    .strength-strong { background: #4CAF50; width: 100%; }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Register</h2>
    <form method="POST" id="registerForm">
      <div class="form-group">
        <label for="name">Full Name:</label>
        <input type="text" name="name" id="name" required>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required oninput="checkPasswordStrength(this.value)">
        <div class="password-strength" id="passwordStrength"></div>
      </div>
      <button type="submit" name="register" class="btn">Register</button>
    </form>
    <script>
      function checkPasswordStrength(password) {
        const strengthBar = document.getElementById('passwordStrength');
        const strength = calculateStrength(password);
        
        strengthBar.className = '';
        strengthBar.style.width = '0%';
        
        if (password.length === 0) return;
        
        if (strength < 2) {
          strengthBar.className = 'strength-weak';
        } else if (strength < 4) {
          strengthBar.className = 'strength-medium';
        } else {
          strengthBar.className = 'strength-strong';
        }
      }
      
      function calculateStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/\d/)) strength++;
        if (password.match(/[^a-zA-Z\d]/)) strength++;
        return strength;
      }
      
      document.getElementById('registerForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        if (!name || !email || !password) {
          e.preventDefault();
          alert('Please fill in all fields');
        } else if (password.length < 8) {
          e.preventDefault();
          alert('Password must be at least 8 characters long');
        }
      });
    </script>
<?php
if (isset($_POST['register'])) {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  
  if (empty($name) || empty($email) || empty($password)) {
    echo '<div class="error">Please fill in all fields</div>';
  } elseif (strlen($password) < 8) {
    echo '<div class="error">Password must be at least 8 characters long</div>';
  } else {
    // Check if email already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    if ($check_stmt->num_rows > 0) {
      echo '<div class="error">Email already registered</div>';
    } else {
      $hashed_password = password_hash($password, PASSWORD_BCRYPT);
      $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $name, $email, $hashed_password);
      
      if ($stmt->execute()) {
        echo '<div style="color:green">Registration successful! <a href="login.php">Login now</a></div>';
      } else {
        echo '<div class="error">Registration failed. Please try again.</div>';
      }
    }
  }
}
?>
</body>
</html>