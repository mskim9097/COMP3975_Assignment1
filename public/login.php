<?php
declare(strict_types=1);

session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: /index.php");
    exit;
}

require_once __DIR__ . "/config.php";

$email = "";
$password = "";

$email_err = "";
$password_err = "";
$login_err = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (trim($_POST["email"] ?? "") === "") {
        $email_err = "Please enter email.";
    } else {
        $email = trim((string)$_POST["email"]);
    }

    if (trim($_POST["password"] ?? "") === "") {
        $password_err = "Please enter your password.";
    } else {
        $password = (string)$_POST["password"];
    }

    if ($email_err === "" && $password_err === "") {
        try {
            $pdo = get_pdo();

            $sql = "SELECT id, email, password_hash FROM users WHERE email = :email LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user["password_hash"])) {
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = (int)$user["id"];
                $_SESSION["email"] = (string)$user["email"];

                header("location: /index.php");
                exit;
            }

            $login_err = "Invalid email or password.";
        } catch (Throwable $e) {
            $login_err = "Server error. Please try again.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
</head>
<body>
  <h1>Admin Login</h1>

  <?php if ($login_err !== ""): ?>
    <p style="color:red;"><?php echo htmlspecialchars($login_err, ENT_QUOTES, "UTF-8"); ?></p>
  <?php endif; ?>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "UTF-8"); ?>" method="post">
    <div>
      <label>Email</label><br>
      <input type="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, "UTF-8"); ?>">
      <div style="color:red;"><?php echo htmlspecialchars($email_err, ENT_QUOTES, "UTF-8"); ?></div>
    </div>

    <br>

    <div>
      <label>Password</label><br>
      <input type="password" name="password">
      <div style="color:red;"><?php echo htmlspecialchars($password_err, ENT_QUOTES, "UTF-8"); ?></div>
    </div>

    <br>

    <button type="submit">Login</button>
  </form>
</body>
</html>
