<?php include '../db.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Admin Login</title><link rel="stylesheet" href="css/style.css"></head>
<body>
<h2>Admin Login</h2>
<form method="POST">
  Email: <input type="email" name="email"><br>
  Password: <input type="password" name="password"><br>
  <input type="submit" name="login" value="Login">
</form>
<?php
if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $hashed);
    $stmt->fetch();
    if (password_verify($password, $hashed)) {
      $_SESSION['admin_id'] = $id;
      header("Location: dashboard.php");
    } else echo "Invalid credentials.";
  } else echo "Admin not found.";
}
?>
</body>
</html>