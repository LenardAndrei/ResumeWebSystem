<?php
session_start();
require 'db.php';

$errors = [];
$success = $_SESSION['success'] ?? null;
unset($_SESSION['success']); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email.';
    if ($password === '') $errors[] = 'Please enter your password.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = 'Invalid email or password.';
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name']; 
            header('Location: resume.php'); exit;
        }
    }
}
?>


<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body class = "login-page">
  <div class = "big-container">
    <div class="container">
      <h2>Login</h2>

      <?php if (!empty($success)): ?>
        <div class="message success"><?=htmlspecialchars($success)?></div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="message error">
          <?php foreach ($errors as $e) echo htmlspecialchars($e) . '<br>'; ?>
        </div>
      <?php endif; ?>

      <form method="post" action="index.php" autocomplete="off" novalidate>
        <div class="form-group">
          <label for="email">Email</label>
          <input id="email" type="email" name="email" autocomplete="off" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input id="password" type="password" name="password" required>
        </div>

        <button class="primary" type="submit">Log In</button>
      </form>

      <div class="small-link">
        Don't have an account? <a href="register.php">Sign Up</a>
      </div>
    </div>
  </div>
</body>
</html>
