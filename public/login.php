<?php
// Initialize session
session_start();


if (isset($_SESSION['logged']) && $_SESSION['logged']) {
    if ($_SESSION['type'] == 'admin') {
        header('Location: admin.php', true, 307);
    } elseif ($_SESSION['type'] == 'user') {
        header('Location: myprofile.php', true, 307);
    }
    exit(0);
}

require_once "database/connection.php";
require_once "utils.php";

$email = $passowrd = "";
$err_message = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if ((isset($_POST['email'])) && (isset($_POST['password']))) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (empty($email) && empty($password)) {
            $err_message = "Inserisci email e password.";
        } elseif (empty($email)) {
            $err_message = "Inserisci email.";
        } elseif (empty($password)) {
            $err_message = "Inserisci password.";
        }

        if ($err_message == "") {

            $db = new Database();
            $conn = $db->openConnection();
            $sql = "SELECT users.id, users.name, users.surname, users.email, users.password
                    FROM users
                    WHERE users.email = :email";

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
                $param_email = strtolower($email);

                if ($stmt->execute()) {
                    if ($stmt->rowCount() == 1) {
                        if ($row = $stmt->fetch()) {
                            $id = $row['id'];
                            $name = $row['name'];
                            $surname = $row['surname'];
                            $email = $row['email'];
                            $hash_psw = $row['password'];

                            if (password_verify($password, $hash_psw)) {
                                // Save session's variables
                                $_SESSION['logged'] = true;
                                $_SESSION["email"] = $email;
                                $_SESSION['name'] = $name;
                                $_SESSION['surname'] = $surname;

                                if (!strcmp($email, "admin@gmail.com")) {
                                    $_SESSION['type'] = 'admin';
                                    header('Location: admin.php', true, 302);
                                } else {
                                    $_SESSION['id'] = $id;
                                    $_SESSION['type'] = 'user';
                                    header('Location: myprofile.php', true, 302);
                                }
                                exit(0);
                            } else {
                                $err_message = "La password inserita non è valida.";
                            }
                        }
                    } else {
                        $err_message = "L'email inserita non è valida.";
                    }
                } else {
                    $err_message = "Oops! Qualcosa è andato storto. Riprova più tardi.";
                }

                $stmt = null;
            }

            $conn = null;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Login</title>
    <!-- Bootstrap Framework -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="img/favicon.ico?v=1" type="image/x-icon">
</head>

<body class="text-center">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">

        <header class="masthead mb-auto">
            <div class="inner">
                <a class="home_logo" href="index.php">
                    <img src="img/logo.png" alt="">
                </a>
                <h2><span class="login_title">T</span>racking <span class="login_title">T</span>ool</h2>
            </div>
        </header>

        <main role="main" class="inner cover">
            <div class="wrapper">
                <p>Benvenuto, Inserisci le credenziali di accesso.</p>
                <div class="frm">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label class="lbl">E-mail</label>
                            <input type="text" name="email" class="form-control login_cred" value="<?php echo $email; ?>" placeholder="E-mail">
                        </div>
                        <div class="form-group <?php echo (!empty($err_message)) ? 'has-error' : ''; ?>">
                            <label class="lbl">Password</label>
                            <input type="password" name="password" class="form-control login_cred" placeholder="Password">
                            <span class="help block text-danger"><?php echo $err_message; ?></span>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Accedi">
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <footer class="mastfoot mt-auto">
            <div class="inner">
                <p>© 2020 Tracking Tool - Developed by Amir & Davide</p>
            </div>
        </footer>
    </div>
</body>

</html>