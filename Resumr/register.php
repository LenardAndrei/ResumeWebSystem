<?php
session_start();
require 'db.php'; // include connection

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($first_name === '') $errors[] = 'First name is required.';
    if ($last_name === '') $errors[] = 'Last name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        // check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $errors[] = "Email is already registered.";
        } else {
            // insert new user
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) 
                                   VALUES (:first_name, :last_name, :email, :password)");
            $stmt->execute([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => $hashed
            ]);
            $_SESSION['success'] = 'Registration successful. You can now log in.';
            header('Location: index.php'); exit;
        }
    }
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Sign up</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body class = "register-page">
  <div class="container">
    <h2>Create account</h2>

    <?php if (!empty($errors)): ?>
      <div class="message error">
        <?php foreach ($errors as $e) echo htmlspecialchars($e) . '<br>'; ?>
      </div>
    <?php endif; ?>

    <form method="post" action="register.php" autocomplete="off" novalidate~>
      <div class="form-group">
        <label for="first_name">First name</label>
        <input id="first_name" type="text" name="first_name" autocomplete="off" required>
      </div>

      <div class="form-group">
        <label for="last_name">Last name</label>
        <input id="last_name" type="text" name="last_name" autocomplete="off" required>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="password">Password (min 8 chars)</label>
        <input id="password" type="password" name="password" required>
      </div>

      <div class="form-group">
        <label for="confirm_password">Confirm password</label>
        <input id="confirm_password" type="password" name="confirm_password" required>
      </div>

      <button class="primary" type="submit">Sign up</button>
    </form>

    <div class="small-link">
      Already have an account? <a href="index.php">Log in</a>
    </div>
  </div>
</body>
</html>
